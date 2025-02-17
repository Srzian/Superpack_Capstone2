<?php
session_start();

// Initialize tasks in session if not already set
if (!isset($_SESSION['tasks'])) {
    $_SESSION['tasks'] = [];
}

// Handle task addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addTask'])) {
    $task = $_POST['task'];
    $employee_name = isset($_POST['employee_name']) ? $_POST['employee_name'] : '';
    $start_date = $_POST['start_date'];
    $due_date = $_POST['due_date'];
    $completion = $_POST['completion'];
    $status = $_POST['status'];
    $priority = $_POST['priority'];
    $department = $_POST['department'];
    
    // Calculate the task duration
    $duration = (strtotime($due_date) - strtotime($start_date)) / (60 * 60 * 24); // in days

    // Add the new task to the session array
    $_SESSION['tasks'][] = [
        'task' => $task,
        'employee_name' => $employee_name,
        'start_date' => $start_date,
        'due_date' => $due_date,
        'completion' => $completion,
        'status' => $status,
        'priority' => $priority,
        'department' => $department,
        'duration' => $duration
    ];

    // Redirect to the same page to refresh the task list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle task deletion
if (isset($_GET['delete'])) {
    $taskIndex = $_GET['delete'];
    unset($_SESSION['tasks'][$taskIndex]);
    $_SESSION['tasks'] = array_values($_SESSION['tasks']); // Re-index the array
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Filter tasks by search keyword
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
$departmentFilter = isset($_POST['department_filter']) ? $_POST['department_filter'] : '';

// Filter tasks based on search and department
$filtered_tasks = array_filter($_SESSION['tasks'], function ($task) use ($searchKeyword, $departmentFilter) {
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
            background-color:#2e3a59;
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
            padding: 18px 25px;
            border-bottom: 1px solid #3e4a72;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background 0.3s ease, transform 0.3s ease;
        }

        .sidebar ul li:hover {
            background-color: #fff;
            transform: translateX(5px);
        }

        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
        }

        /* Modal Styling */
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

        /* Task Table and Filter */
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

        /* Add Task Button */
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

        /* Task Form Styling */
        .modal input, .modal select {
            margin-bottom: 10px;
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
        <li><a href="#">Dashboard</a></li>
        <li><a href="#">Payroll</a></li>
        <li><a href="#">Employee Management</a></li>
        <li><a href="task_management.php">Task Management</a></li>
        <li><a href="#">Attendance</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="#">About Company</a></li>
        <li><a href="#">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1>Task Management</h1>

    <!-- Add Task Button -->
    <div class="filters">
        <button class="add-task-btn" onclick="document.getElementById('task-modal').style.display = 'flex';">Add New Task</button>
        <form method="POST" class="filters">
            <input type="text" name="search" placeholder="Search tasks" value="<?php echo $searchKeyword; ?>" />
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

    <!-- Modal (Pop-up) for Adding New Task -->
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
                    <option value="Low">Low</option>
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
                <th>Duration (Days)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_tasks as $index => $task): ?>
                <tr>
                    <td><?php echo htmlspecialchars($task['task']); ?></td>
                    <td><?php echo htmlspecialchars($task['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($task['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($task['due_date']); ?></td>
                    <td><?php echo htmlspecialchars($task['completion']); ?>%</td>
                    <td><?php echo htmlspecialchars($task['status']); ?></td>
                    <td><?php echo htmlspecialchars($task['priority']); ?></td>
                    <td><?php echo htmlspecialchars($task['department']); ?></td>
                    <td><?php echo $task['duration']; ?> days</td>
                    <td>
                        <a href="?delete=<?php echo $index; ?>" class="action-btn">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
