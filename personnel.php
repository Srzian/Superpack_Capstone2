<?php
session_start();

// Initialize personnel records in session if not already set
if (!isset($_SESSION['personnel_records'])) {
    $_SESSION['personnel_records'] = [];
}

// Handle record addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRecord'])) {
    // Retrieve all fields
    $employee_name = $_POST['employee_name'];
    $email = $_POST['email'];
    $cell_phone = $_POST['cell_phone'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $marital_status = $_POST['marital_status'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    $job_title = $_POST['job_title'];
    $employee_id = $_POST['employee_id'];
    $start_date = $_POST['start_date'];
    $department = $_POST['department'];
    $salary = $_POST['salary'];
    $work_location = $_POST['work_location'];
    $supervisor = $_POST['supervisor'];
    $status = $_POST['status'];

    // Add the new record to the session array
    $_SESSION['personnel_records'][] = [
        'employee_name' => $employee_name,
        'email' => $email,
        'cell_phone' => $cell_phone,
        'address' => $address,
        'birth_date' => $birth_date,
        'marital_status' => $marital_status,
        'emergency_contact_name' => $emergency_contact_name,
        'emergency_contact_number' => $emergency_contact_number,
        'job_title' => $job_title,
        'employee_id' => $employee_id,
        'start_date' => $start_date,
        'department' => $department,
        'salary' => $salary,
        'work_location' => $work_location,
        'supervisor' => $supervisor,
        'status' => $status,
    ];

    // Redirect to the same page to refresh the records list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $recordIndex = (int)$_GET['delete']; // Ensure the index is an integer
    if (isset($_SESSION['personnel_records'][$recordIndex])) {
        unset($_SESSION['personnel_records'][$recordIndex]); // Remove the record
        $_SESSION['personnel_records'] = array_values($_SESSION['personnel_records']); // Re-index the array
    }
    header('Location: ' . $_SERVER['PHP_SELF']); // Refresh the page
    exit;
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

        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px);
            transition: margin-left 0.3s ease;
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

        .record-table {
            width: 100%;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            table-layout: fixed;
        }

        .record-table thead {
            background-color: #2e3a59;
            color: white;
        }

        .record-table th, .record-table td {
            padding: 12px 15px;
            text-align: left;
            word-wrap: break-word;
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
        }

        .modal-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 600px; /* Increased width for a more rectangular shape */
            max-height: 80vh; /* Limit the height of the modal */
            overflow-y: auto; /* Enable scrolling if content exceeds max height */
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
        }

        .modal button:hover {
            background-color: #357ab7;
        }

        /* Dropdown Menu Styles */
        .sidebar ul li .dropdown {
            display: none; /* Initially hidden */
            background-color: #3e4a72; /* Set the background color */
            padding: 10px 0; /* Optional: Add some padding */
            border-radius: 4px; /* Optional: Add rounded corners */
        }

        .sidebar ul li .dropdown li {
            padding: 10px 20px; /* Padding for dropdown items */
        }

        .sidebar ul li .dropdown li a {
            color: white; /* Set text color for dropdown items */
        }

        @media print {
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }

            .sidebar, .content {
                display: none;
            }

            .print-container {
                width: 100%;
                padding: 20px;
            }

            .print-container table {
                width: 100%;
                border-collapse: collapse;
            }

            .print-container th,
            .print-container td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            .print-container th {
                background-color: #2e3a59;
                color: white;
            }

            .company-logo {
                width: 100px;
                height: auto;
            }

            .print-btn {
                display: none;
            }
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
            <a href="javascript:void(0)" class="dropdown-toggle" onclick="toggleDropdown(this)"><i class="fas fa-users"></i> Employee Management <i class="fas fa-chevron-down arrow"></i></a>
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
    <h1>Personnel Records</h1>

    <!-- Add Record Button -->
    <div class="filters">
        <button class="add-record-btn" onclick="document.getElementById('record-modal').style.display = 'flex';">Add New Record</button>
        <form method="POST" class="filters">
            <input type="text" name="search" placeholder="Search employees" value="<?php echo htmlspecialchars($searchKeyword); ?>" />
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
                <th>Email</th>
                <th>Cell Phone</th>
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
                    <td><?php echo htmlspecialchars($record['email']); ?></td>
                    <td><?php echo htmlspecialchars($record['cell_phone']); ?></td>
                    <td><?php echo htmlspecialchars($record['job_title']); ?></td>
                    <td><?php echo htmlspecialchars($record['department']); ?></td>
                    <td><?php echo htmlspecialchars($record['start_date']); ?></td>
                    <td><?php echo htmlspecialchars($record['status']); ?></td>
                    <td>
                        <a href="?delete=<?php echo $index; ?>" class="action-btn">Delete</a>
                        <button onclick="toggleSeeMore(<?php echo $index; ?>)" class="action-btn">See More</button>
                        <button onclick="printRecord(<?php echo $index; ?>)" class="action-btn">Print</button>
                    </td>
                </tr>

                <!-- Hidden Expanded Details -->
                <tr id="expand-row-<?php echo $index; ?>" style="display: none;">
                    <td colspan="8">
                        <table>
                            <tr>
                                <th>Address</th>
                                <td><?php echo htmlspecialchars($record['address']); ?></td>
                            </tr>
                            <tr>
                                <th>Birth Date</th>
                                <td><?php echo htmlspecialchars($record['birth_date']); ?></td>
                            </tr>
                            <tr>
                                <th>Marital Status</th>
                                <td><?php echo htmlspecialchars($record['marital_status']); ?></td>
                            </tr>
                            <tr>
                                <th>Emergency Contact Name</th>
                                <td><?php echo htmlspecialchars($record['emergency_contact_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Emergency Contact Number</th>
                                <td><?php echo htmlspecialchars($record['emergency_contact_number']); ?></td>
                            </tr>
                            <tr>
                                <th>Salary</th>
                                <td><?php echo htmlspecialchars($record['salary']); ?></td>
                            </tr>
                            <tr>
                                <th>Work Location</th>
                                <td><?php echo htmlspecialchars($record['work_location']); ?></td>
                            </tr>
                            <tr>
                                <th>Supervisor</th>
                                <td><?php echo htmlspecialchars($record['supervisor']); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal for Adding Record -->
    <div id="record-modal" class="modal">
        <div class="modal-content">
            <h2>Add Personnel Record</h2>
            <form method="POST">
                <h3>Employee Information</h3>
                <label for="employee_name">Employee Name:</label>
                <input type="text" name="employee_name" placeholder="Employee Name" required>

                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="E-mail" required>

                <label for="cell_phone">Cell Phone:</label>
                <input type="text" name="cell_phone" placeholder="Cell Phone" required>

                <label for="address">Address:</label>
                <input type="text" name="address" placeholder="Address" required>

                <label for="birth_date">Birth Date:</label>
                <input type="date" name="birth_date" required>

                <label for="marital_status">Marital Status:</label>
                <select name="marital_status" required>
                    <option value="">Select Marital Status</option>
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                    <option value="Widowed">Widowed</option>
                </select>

                <h3>Emergency Contact</h3>
                <label for="emergency_contact_name">Emergency Contact Name:</label>
                <input type="text" name="emergency_contact_name" placeholder="Emergency Contact Name (Optional)">

                <label for="emergency_contact_number">Emergency Contact Number:</label>
                <input type="text" name="emergency_contact_number" placeholder="Emergency Contact Number (Optional)">

                <h3>Work Information</h3>
                <label for="job_title">Job Title:</label>
                <input type="text" name="job_title" placeholder="Job Title" required>

                <label for="department">Department:</label>
                <input type="text" name="department" placeholder="Department" required>

                <label for="start_date">Date of Hire:</label>
                <input type="date" name="start_date" required>

                <label for="salary">Salary:</label>
                <input type="number" name="salary" placeholder="Salary" required>

                <label for="work_location">Work Location:</label>
                <input type="text" name="work_location" placeholder="Work Location" required>

                <label for="supervisor">Supervisor:</label>
                <input type="text" name="supervisor" placeholder="Supervisor" required>

                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="On Leave">On Leave</option>
                </select>

                <button type="submit" name="addRecord">Add Record</button>
                <button type="button" onclick="document.getElementById('record-modal').style.display = 'none';">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        function toggleDropdown(element) {
            const dropdown = element.nextElementSibling;
            const allDropdowns = document.querySelectorAll('.dropdown');

            // Close all other dropdowns
            allDropdowns.forEach(function (d) {
                if (d !== dropdown) {
                    d.style.display = 'none';
                }
            });

            // Toggle the clicked dropdown
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        // Close dropdowns when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.dropdown-toggle')) {
                const allDropdowns = document.querySelectorAll('.dropdown');
                allDropdowns.forEach(function (d) {
                    d.style.display = 'none';
                });
            }
        };

        function toggleSeeMore(index) {
            const row = document.getElementById('expand-row-' + index);
            row.style.display = row.style.display === 'none' ? 'table-row' : 'none';
        }

        function printRecord(index) {
            const record = <?php echo json_encode($_SESSION['personnel_records']); ?>[index];
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print Record</title>
                        <style>
                            body {
                                font-family: Arial, sans-serif;
                                margin: 20px;
                            }
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 1px solid #ddd;
                                padding: 8px;
                                text-align: left;
                            }
                            th {
                                background-color: #2e3a59;
                                color: white;
                            }
                            .company-logo {
                                width: 100px;
                                height: auto;
                            }
                        </style>
                    </head>
                    <body>
                        <img src="LOGO.png" alt="Company Logo" class="company-logo">
                        <h2>Personnel Record</h2>
                        <table>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                            <tr><td>Employee Name</td><td>${record.employee_name}</td></tr>
                            <tr><td>Email</td><td>${record.email}</td></tr>
                            <tr><td>Cell Phone</td><td>${record.cell_phone}</td></tr>
                            <tr><td>Job Title</td><td>${record.job_title}</td></tr>
                            <tr><td>Department</td><td>${record.department}</td></tr>
                            <tr><td>Date of Hire</td><td>${record.start_date}</td></tr>
                            <tr><td>Status</td><td>${record.status}</td></tr>
                            <tr><td>Address</td><td>${record.address}</td></tr>
                            <tr><td>Birth Date</td><td>${record.birth_date}</td></tr>
                            <tr><td>Marital Status</td><td>${record.marital_status}</td></tr>
                            <tr><td>Emergency Contact Name</td><td>${record.emergency_contact_name}</td></tr>
                            <tr><td>Emergency Contact Number</td><td>${record.emergency_contact_number}</td></tr>
                            <tr><td>Salary</td><td>${record.salary}</td></tr>
                            <tr><td>Work Location</td><td>${record.work_location}</td></tr>
                            <tr><td>Supervisor</td><td>${record.supervisor}</td></tr>
                        </table>
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.print();
        }
    </script>
</body>
</html>
