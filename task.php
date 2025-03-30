<?php
session_start();

// Initialize tasks in session if not already set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Initialize the search keyword variable
$searchKeyword = ''; // Initialize the search keyword variable
$departmentFilter = ''; // Initialize the department filter variable

// Database connection parameters
$host = 'localhost';
$username = 'root'; // Default username for XAMPP
$password = ''; // Default password for XAMPP (usually empty)
$database = 'task_management'; // Your database name

// Create a connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $task = $_POST['task'];
    $employee_name = $_POST['employee_name'] ?? '';
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $completion = $_POST['completion'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $department = $_POST['department'];

    // Insert the new task into the database
    $stmt = $conn->prepare("INSERT INTO tasks (task, employee_name, start_date, due_date, completion, status, priority, department) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssisss", $task, $employee_name, $start_date, $due_date, $completion, $status, $priority, $department);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to refresh the task list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->close();

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle task editing
if (isset($_POST['editTask'])) {
    $taskId = $_POST['task_index'];
    $completion = $_POST['completion'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];

    // Update the task in the database
    $stmt = $conn->prepare("UPDATE tasks SET completion = ?, status = ?, priority = ? WHERE id = ?");
    $stmt->bind_param("sssi", $completion, $status, $priority, $taskId);
    $stmt->execute();
    $stmt->close();

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch tasks from the database
$result = $conn->query("SELECT * FROM tasks");
$tasks = $result->fetch_all(MYSQLI_ASSOC);

// Filter tasks by search keyword
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
$departmentFilter = isset($_POST['department_filter']) ? $_POST['department_filter'] : '';

// Filter tasks based on search and department
$filtered_tasks = array_filter($tasks, function ($task) use ($searchKeyword, $departmentFilter) {
    $matchesSearch = !$searchKeyword || stripos($task['task'], $searchKeyword) !== false;
    $matchesDepartment = !$departmentFilter || $task['department'] === $departmentFilter;
    return $matchesSearch && $matchesDepartment;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Sidebar and general styling */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #2e3a59; /* Sleek dark blue */
            color: white;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 20px;
            z-index: 1000;
            padding-left: 20px;
        }

        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            width: 120px;
            height: auto;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            border-bottom: 1px solid #3e4a72;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .sidebar ul li:hover {
            background-color: #4a90e2;
            transform: translateX(5px);
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            font-size: 16px;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .sidebar ul li i {
            margin-right: 15px;
        }

        .sidebar .dropdown {
            display: none;
            flex-direction: column;
            padding-left: 20px;
            background-color: #364f6b;
        }

        .sidebar .dropdown.open {
            display: flex;
        }

        .sidebar .dropdown li {
            padding: 12px 20px;
            font-size: 15px;
            border-bottom: 1px solid #3e4a72;
            border-radius: 4px;
        }

        .sidebar .dropdown li:hover {
            background-color: #4a90e2;
        }

        .sidebar ul li .arrow {
            transition: transform 0.3s ease;
        }

        .rotate {
            transform: rotate(180deg);
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            animation: fadeIn 0.3s ease;
        }

        .modal-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .modal input, .modal select, .modal button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        .modal button {
            background-color: #4a90e2;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal button:hover {
            background-color: #357ab7;
        }

        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filters input, .filters button, .filters select {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
            margin-left: 10px;
        }

        .task-table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .task-table thead {
            background-color: #2e3a59;
            color: white;
        }

        .task-table th, .task-table td {
            padding: 15px;
            text-align: left;
        }

        .task-table th {
            font-size: 16px;
        }

        .task-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .task-table .action-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .task-table .action-btn:hover {
            background-color: #357ab7;
        }

        .add-task-btn {
            padding: 15px 25px;
            background-color: #4a90e2;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-task-btn:hover {
            background-color: #357ab7;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <div class="logo">
        <img src="LOGO.png" alt="Superpack Logo">
    </div>
    <ul>
        <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
        <li><a href="payroll.php"><i class="fas fa-file-invoice-dollar"></i> Payroll</a></li>
        <li class="menu-item">
            <a href="javascript:void( 0)" class="dropdown-toggle" onclick="toggleDropdown(this)"><i class="fas fa-users"></i> Employee Management <i class="fas fa-chevron-down arrow"></i></a>
            <ul class="dropdown">
                <li><a href="personnel.php"><i class="fas fa-id-badge"></i> Personnel Records</a></li>
                <li><a href="leave.php"><i class="fas fa-plane"></i> Leave Request</a></li>
                <li><a href="evaluation.php"><i class="fas fa-star"></i> Evaluation Form</a></li>
            </ul>
        </li>
        <li><a href="task.php"><i class="fa-solid fa-font-awesome"></i> Task Management</a></li>
        <li><a href="attendance.php"><i class="fa-solid fa-star"></i> Attendance</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="about.php"><i class="fas fa-building"></i> About Company</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1>Task Management</h1>

    <!-- Add Task Button -->
    <div class="filters">
        <button class="add-task-btn" onclick="document.getElementById('task-modal').style.display = 'flex';">Add New Task</button>
        <form method="POST" class="filters">
            <input type="text" name="search" placeholder="Search tasks" value="<?php echo htmlspecialchars($searchKeyword); ?>" />
            <button type="submit">Search</button>

            <!-- Department Filter -->
            <select name="department_filter" id="department_filter">
                <option value="">Select Department</option>
                <option value="Logistics" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Logistics') ? 'selected' : ''; ?>>Logistics</option>
                <option value="Purchasing" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Purchasing') ? 'selected' : ''; ?>>Purchasing</option>
                <option value="Purchase Development" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Purchase Development') ? 'selected' : ''; ?>>Purchase Development</option>
                <option value="Accounting" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                <option value="Sales" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                <option value="Warehouse" <?php echo (isset($_POST['department_filter']) && $_POST['department_filter'] == 'Warehouse') ? 'selected' : ''; ?>>Warehouse</option>
            </select>

            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Modal for Adding New Task -->
    <div id="task-modal" class="modal">
        <div class="modal-content">
            <h2>Add Task</h2>
            <form method="POST">
                <label for="task">Task:</label>
                <input type="text" id="task" name="task" required><br>

                <label for="employee_name">Employee Name:</label>
                <input type="text" id="employee_name" name="employee_name" required><br>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required><br>

                <label for="due_date">Due Date:</label>
                <input type="date" id="due_date" name="due_date" required><br>

                <label for="completion">Completion %:</label>
                <input type="number" id="completion" name="completion" min="0" max="100" required><br>

                <label for="status">Status:</label>
                <select name="status" id="status" required>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                </select><br>

                <label for="priority">Priority:</label>
                <select name="priority" id="priority" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low"> Low</option>
                </select><br>

                <label for="department">Department:</label>
                <select name="department" id="department" required>
                    <option value="Logistics">Logistics</option>
                    <option value="Purchasing">Purchasing</option>
                    <option value="Purchase Development">Purchase Development</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Sales">Sales</option>
                    <option value="Warehouse">Warehouse</option>
                </select><br>

                <button type="submit" name="addTask">Add Task</button>
                <button type="button" onclick="document.getElementById('task-modal').style.display = 'none';">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Modal for Editing Task -->
    <div id="edit-task-modal" class="modal">
        <div class="modal-content">
            <h2>Edit Task</h2>
            <form method="POST" id="edit-task-form">
                <input type="hidden" name="task_index" id="task_index">
                <label for="edit_completion">Completion %:</label>
                <input type="number" id="edit_completion" name="completion" min="0" max="100" required><br>

                <label for="edit_status">Status:</label>
                <select name="status" id="edit_status" required>
                    <option value="In Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                </select><br>

                <label for="edit_priority">Priority:</label>
                <select name="priority" id="edit_priority" required>
                    <option value="High">High</option>
                    <option value="Medium">Medium</option>
                    <option value="Low">Low</option>
                </select><br>

                <button type="submit" name="editTask">Update Task</button>
                <button type="button" onclick="document.getElementById('edit-task-modal').style.display = 'none';">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Task Table -->
    <table class="task-table">
        <thead>
            <tr>
                <th>Task</th>
                <th>Employee Name</th>
                <th>Start Date</th>
                <th>Due Date</th>
                <th>Completion</th>
                <th>Status</th>
                <th>Priority</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['task']); ?></td>
                    <td><?php echo htmlspecialchars($task['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($task['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($task['completion']); ?>%</td>
                    <td><?php echo htmlspecialchars($task['status']); ?></td>
                    <td><?php echo htmlspecialchars($task['priority']); ?></td>
                    <td><?php echo htmlspecialchars($task['department']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $task['id']; ?>" class="action-btn">Delete</a>
                        <button type="button" class="action-btn" onclick="openEditModal(<?php echo $task['id']; ?>, '<?php echo htmlspecialchars($task['completion']); ?>', '<?php echo htmlspecialchars($task['status']); ?>', '<?php echo htmlspecialchars($task['priority']); ?>')">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    function openEditModal(id, completion, status, priority) {
        document.getElementById('task_index').value = id;
        document.getElementById('edit_completion').value = completion;
        document.getElementById('edit_status').value = status;
        document.getElementById('edit_priority').value = priority;
        document.getElementById('edit-task-modal').style.display = 'flex';
    }

    function toggleDropdown(element) {
        const dropdown = element.nextElementSibling;
        dropdown.classList.toggle('open');
        const arrow = element.querySelector('.arrow');
        arrow.classList.toggle('rotate');
    }
</script>

</body>
</html>
