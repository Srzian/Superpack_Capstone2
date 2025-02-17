<?php
session_start();

// Initialize personnel records in session if not already set
if (!isset($_SESSION['personnel_records'])) {
    $_SESSION['personnel_records'] = [];
}

// Handle record addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRecord'])) {
    $employee_name = $_POST['employee_name'];
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];
    $date_of_hire = $_POST['date_of_hire'];
    $status = $_POST['status'];

    // New fields
    $address = $_POST['address'];
    $zip_code = $_POST['zip_code'];
    $gender = $_POST['gender'];
    $emergency_contact = $_POST['emergency_contact'];

    // Add the new record to the session array
    $_SESSION['personnel_records'][] = [
        'employee_name' => $employee_name,
        'job_title' => $job_title,
        'department' => $department,
        'date_of_hire' => $date_of_hire,
        'status' => $status,
        'address' => $address,
        'zip_code' => $zip_code,
        'gender' => $gender,
        'emergency_contact' => $emergency_contact,
    ];

    // Redirect to the same page to refresh the records list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $recordIndex = $_GET['delete'];
    unset($_SESSION['personnel_records'][$recordIndex]);
    $_SESSION['personnel_records'] = array_values($_SESSION['personnel_records']); // Re-index the array
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle record edit
if (isset($_GET['edit'])) {
    $recordIndex = $_GET['edit'];
    $recordToEdit = $_SESSION['personnel_records'][$recordIndex];
}

// Filter records by search keyword
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
$departmentFilter = isset($_POST['department_filter']) ? $_POST['department_filter'] : '';

// Filter records based on search and department
$filtered_records = array_filter($_SESSION['personnel_records'], function ($record) use ($searchKeyword, $departmentFilter) {
    $matchesSearch = !$searchKeyword || stripos($record['employee_name'], $searchKeyword) !== false;
    $matchesDepartment = !$departmentFilter || $record['department'] === $departmentFilter;
    return $matchesSearch && $matchesDepartment;
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Records</title>
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
            background-color: #2e3a59;
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

        /* Record Table and Filter */
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

        .record-table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            table-layout: fixed; /* Ensure consistent table layout */
        }

        .record-table thead {
            background-color: #2e3a59;
            color: white;
        }

        .record-table th, .record-table td {
            padding: 12px 15px; /* Adjusted padding for more consistent spacing */
            text-align: left;
            word-wrap: break-word; /* Ensure long words break and fit the table */
        }

        .record-table th {
            font-size: 16px;
        }

        .record-table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .record-table .action-btn {
            background-color: #4a90e2;
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .record-table .action-btn:hover {
            background-color: #357ab7;
        }

        /* Add Record Button */
        .add-record-btn {
            padding: 15px 25px;
            background-color: #4a90e2;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .add-record-btn:hover {
            background-color: #357ab7;
        }

        /* Expand Section */
        .expandable-content {
            display: none;
            margin-top: 10px;
        }

        .expand-btn {
            background-color: #f4f6f9;
            padding: 8px;
            border: none;
            cursor: pointer;
            color: #4a90e2;
        }

        .expand-btn:hover {
            background-color: #e0e0e0;
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
        <li><a href="personnel_records.php">Personnel Records</a></li>
        <li><a href="#">Attendance</a></li>
        <li><a href="#">Settings</a></li>
        <li><a href="#">About Company</a></li>
        <li><a href="#">Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1>Personnel Records</h1>

    <!-- Add Record Button -->
    <div class="filters">
        <button class="add-record-btn" onclick="document.getElementById('record-modal').style.display = 'flex';">Add New Record</button>
        <form method="POST" class="filters">
            <input type="text" name="search" placeholder="Search employees" value="<?php echo $searchKeyword; ?>" />
            <button type="submit">Search</button>

            <!-- Department Filter -->
            <select name="department_filter" id="department_filter">
                <option value="">Select Department</option>
                <option value="Logistics" <?php echo ($departmentFilter == 'Logistics') ? 'selected' : ''; ?>>Logistics</option>
                <option value="Purchasing" <?php echo ($departmentFilter == 'Purchasing') ? 'selected' : ''; ?>>Purchasing</option>
                <option value="Purchase Development" <?php echo ($departmentFilter == 'Purchase Development') ? 'selected' : ''; ?>>Purchase Development</option>
                <option value="Accounting" <?php echo ($departmentFilter == 'Accounting') ? 'selected' : ''; ?>>Accounting</option>
                <option value="Sales" <?php echo ($departmentFilter == 'Sales') ? 'selected' : ''; ?>>Sales</option>
                <option value="Finance" <?php echo ($departmentFilter == 'Finance') ? 'selected' : ''; ?>>Finance</option>
            </select>
        </form>
    </div>

    <!-- Personnel Records Table -->
    <table class="record-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Job Title</th>
                <th>Department</th>
                <th>Date of Hire</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_records as $index => $record): ?>
                <tr>
                    <td><?php echo htmlspecialchars($record['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($record['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($record['department']); ?></td>
                    <td><?php echo htmlspecialchars($record['date_of_hire']); ?></td>
                    <td><?php echo htmlspecialchars($record['status']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $index; ?>" class="action-btn">Delete</a>
                        <a href="?edit=<?php echo $index; ?>" class="action-btn">Edit</a>
                        <button class="expand-btn" onclick="toggleSeeMore(<?php echo $index; ?>)">See More</button>
                    </td>
                </tr>
                <tr id="expand-row-<?php echo $index; ?>" class="expandable-content">
                    <td colspan="6">
                        <div><strong>Address:</strong> <?php echo $record['address']; ?></div>
                        <div><strong>Zip Code:</strong> <?php echo $record['zip_code']; ?></div>
                        <div><strong>Gender:</strong> <?php echo $record['gender']; ?></div>
                        <div><strong>Emergency Contact:</strong> <?php echo $record['emergency_contact']; ?></div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Add Record Modal -->
    <div id="record-modal" class="modal">
        <div class="modal-content">
            <h2>Add New Personnel Record</h2>
            <form method="POST">
                <input type="text" name="employee_name" placeholder="Employee Name" required>
                <input type="text" name="job_title" placeholder="Job Title" required>
                <select name="department" required>
                    <option value="">Select Department</option>
                    <option value="Logistics">Logistics</option>
                    <option value="Purchasing">Purchasing</option>
                    <option value="Purchase Development">Purchase Development</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Sales">Sales</option>
                    <option value="Finance">Finance</option>
                </select>
                <input type="date" name="date_of_hire" placeholder="Date of Hire" required>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
                <!-- New Fields -->
                <input type="text" name="address" placeholder="Address" required>
                <input type="text" name="zip_code" placeholder="Zip Code" required>
                <select name="gender" required>
                    <option value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <input type="text" name="emergency_contact" placeholder="Emergency Contact" required>
                <button type="submit" name="addRecord">Add Record</button>
                <button type="button" onclick="document.getElementById('record-modal').style.display = 'none';">Cancel</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle the "See More" section
    function toggleSeeMore(index) {
        const row = document.getElementById('expand-row-' + index);
        if (row.style.display === 'none') {
            row.style.display = 'table-row';
        } else {
            row.style.display = 'none';
        }
    }
</script>

</body>
</html>
