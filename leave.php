<?php
session_start();

// Initialize leave request records in session if not already set
if (!isset($_SESSION['leave_requests'])) {
    $_SESSION['leave_requests'] = [];
}

// Handle record addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addRequest'])) {
    $employee_name = $_POST['employee_name'];
    $email = $_POST['email'];
    $cell_phone = $_POST['cell_phone'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $marital_status = $_POST['marital_status'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];
    $leave_type = $_POST['leave_type'];
    $leave_start_date = $_POST['leave_start_date'];
    $leave_end_date = $_POST['leave_end_date'];
    $leave_reason = $_POST['leave_reason'];
    $status = $_POST['status'];
    $active = $_POST['active'];

    // Add the new leave request to the session array
    $_SESSION['leave_requests'][] = [
        'employee_name' => $employee_name,
        'email' => $email,
        'cell_phone' => $cell_phone,
        'address' => $address,
        'birth_date' => $birth_date,
        'marital_status' => $marital_status,
        'emergency_contact_name' => $emergency_contact_name,
        'emergency_contact_number' => $emergency_contact_number,
        'job_title' => $job_title,
        'department' => $department,
        'leave_type' => $leave_type,
        'leave_start_date' => $leave_start_date,
        'leave_end_date' => $leave_end_date,
        'leave_reason' => $leave_reason,
        'status' => $status,
        'active' => $active,
    ];

    // Redirect to the same page to refresh the leave requests list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle record deletion
if (isset($_GET['delete'])) {
    $recordIndex = $_GET['delete'];
    if (isset($_SESSION['leave_requests'][$recordIndex])) {
        unset($_SESSION['leave_requests'][$recordIndex]);
        $_SESSION['leave_requests'] = array_values($_SESSION['leave_requests']); // Re-index the array
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle record editing (edit status and active/inactive)
if (isset($_POST['editRequest'])) {
    $recordIndex = $_POST['recordIndex'];
    $employee_name = $_POST['employee_name'];
    $email = $_POST['email'];
    $cell_phone = $_POST['cell_phone'];
    $address = $_POST['address'];
    $birth_date = $_POST['birth_date'];
    $marital_status = $_POST['marital_status'];
    $emergency_contact_name = $_POST['emergency_contact_name'];
    $emergency_contact_number = $_POST['emergency_contact_number'];
    $job_title = $_POST['job_title'];
    $department = $_POST['department'];
    $leave_type = $_POST['leave_type'];
    $leave_start_date = $_POST['leave_start_date'];
    $leave_end_date = $_POST['leave_end_date'];
    $leave_reason = $_POST['leave_reason'];
    $status = $_POST['status'];
    $active = $_POST['active'];

    // Update the record in the session array
    if (isset($_SESSION['leave_requests'][$recordIndex])) {
        $_SESSION['leave_requests'][$recordIndex] = [
            'employee_name' => $employee_name,
            'email' => $email,
            'cell_phone' => $cell_phone,
            'address' => $address,
            'birth_date' => $birth_date,
            'marital_status' => $marital_status,
            'emergency_contact_name' => $emergency_contact_name,
            'emergency_contact_number' => $emergency_contact_number,
            'job_title' => $job_title,
            'department' => $department,
            'leave_type' => $leave_type,
            'leave_start_date' => $leave_start_date,
            'leave_end_date' => $leave_end_date,
            'leave_reason' => $leave_reason,
            'status' => $status,
            'active' => $active,
        ];
    }

    // Redirect to the same page to refresh the leave requests list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Filter records by search keyword
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';

// Filter leave requests based on search
$filtered_requests = array_filter($_SESSION['leave_requests'], function ($request) use ($searchKeyword) {
    return !$searchKeyword || stripos($request['employee_name'], $searchKeyword) !== false;
});

// Check if we are editing a request
$editIndex = isset($_GET['edit']) ? $_GET['edit'] : null;
$editRequest = $editIndex !== null ? $_SESSION['leave_requests'][$editIndex] : null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Requests</title>
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
            border: none;
            border-radius: 4px;
            cursor: pointer;
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
            padding: 30px;
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

        h3 {
            margin: 20px 0 10px;
            font-size: 20px;
            color: #333;
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
        <li><a href="payroll.php"><i class="fas fa-money-bill"></i> Payroll</a></li>
        <li class="menu-item">
            <a href="javascript:void(0)" class="dropdown-toggle" onclick="toggleDropdown(this)"><i class="fas fa-users"></i> Employee Management <i class="fas fa-chevron-down arrow"></i></a>
            <ul class="dropdown" style="background-color: #364f6b; color: white; display: none;">
                <li><a href="personnel.php" style="color: white;"><i class="fas fa-id-badge"></i> Personnel Records</a></li>
                <li><a href="leave.php" style="color: white;"><i class="fas fa-plane"></i> Leave Request</a></li>
                <li><a href="evaluation.php" style="color: white;"><i class="fas fa-star"></i> Evaluation Form</a></li>
            </ul>
        </li>
        <li><a href="task.php"><i class="fa-solid fa-tasks"></i> Task Management</a></li>
        <li><a href="attendance.php"><i class="fa-solid fa-clock"></i> Attendance</a></li>
        <li><a href="settings.php"><i class="fas fa-cog"></i> Settings</a></li>
        <li><a href="about.php"><i class="fas fa-building"></i> About Company</a></li>
        <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="content">
    <h1>Leave Requests</h1>

    <!-- Add Leave Request Button -->
    <div class="filters">
        <button class="add-record-btn" onclick="document.getElementById('request-modal').style.display = 'flex';">Add New Request</button>
        <form method="POST" class="filters">
            <input type="text" name="search" placeholder="Search employees" value="<?php echo $searchKeyword; ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Leave Requests Table -->
    <table class="record-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Leave Type</th>
                <th>Leave Dates</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Active/Inactive</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_requests as $index => $request): ?>
                <tr>
                    <td><?php echo htmlspecialchars($request['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($request['leave_type']); ?></td>
                    <td><?php echo htmlspecialchars($request['leave_start_date']) . ' to ' . htmlspecialchars($request['leave_end_date']); ?></td>
                    <td><?php echo htmlspecialchars($request['leave_reason']); ?></td>
                    <td><?php echo htmlspecialchars($request['status']); ?></td>
                    <td><?php echo htmlspecialchars($request['active']); ?></td>
                    <td style="display: flex; gap: 10px;">
                        <a href="?delete=<?php echo $index; ?>" class="action-btn delete">Delete</a>
                        <a href="?edit=<?php echo $index; ?>" class="action-btn edit">Edit</a>
                        <button onclick="seeMore(<?php echo $index; ?>)" class="action-btn">See More</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal for Adding or Editing Leave Request -->
    <div id="request-modal" class="modal" style="display: <?php echo $editRequest ? 'flex' : 'none'; ?>">
        <div class="modal-content">
            <h2><?php echo $editRequest ? 'Edit Leave Request' : 'Add Leave Request'; ?></h2>
            <form method="POST">
                <h3>Employee Information</h3>
                <label for="employee_name">Employee Name:</label>
                <input type="text" name="employee_name" placeholder="Employee Name" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['employee_name']) : ''; ?>">

                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="E-mail" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['email']) : ''; ?>">

                <label for="cell_phone">Cell Phone:</label>
                <input type="text" name="cell_phone" placeholder="Cell Phone" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['cell_phone']) : ''; ?>">

                <label for="address">Address:</label>
                <input type="text" name="address" placeholder="Address" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['address']) : ''; ?>">

                <label for="birth_date">Birth Date:</label>
                <input type="date" name="birth_date" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['birth_date']) : ''; ?>">

                <label for="marital_status">Marital Status:</label>
                <select name="marital_status" required>
                    <option value="">Select Marital Status</option>
                    <option value="Single" <?php echo $editRequest && $editRequest['marital_status'] == 'Single' ? 'selected' : ''; ?>>Single</option>
                    <option value="Married" <?php echo $editRequest && $editRequest['marital_status'] == 'Married' ? 'selected' : ''; ?>>Married</option>
                    <option value="Divorced" <?php echo $editRequest && $editRequest['marital_status'] == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                    <option value="Widowed" <?php echo $editRequest && $editRequest['marital_status'] == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                </select>

                <h3>Emergency Contact</h3>
                <label for="emergency_contact_name">Emergency Contact Name:</label>
                <input type="text" name="emergency_contact_name" placeholder="Emergency Contact Name (Optional)" value="<?php echo $editRequest ? htmlspecialchars($editRequest['emergency_contact_name']) : ''; ?>">

                <label for="emergency_contact_number">Emergency Contact Number:</label>
                <input type="text" name="emergency_contact_number" placeholder="Emergency Contact Number (Optional)" value="<?php echo $editRequest ? htmlspecialchars($editRequest['emergency_contact_number']) : ''; ?>">

                <h3>Work Information</h3>
                <label for="job_title">Job Title:</label>
                <input type="text" name="job_title" placeholder="Job Title" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['job_title']) : ''; ?>">

                <label for="department">Department:</label>
                <input type="text" name="department" placeholder="Department" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['department']) : ''; ?>">

                <h3>Leave Information</h3>
                <label for="leave_type">Type of Leave:</label>
                <select name="leave_type" required>
                    <option value="">Select Leave Type</option>
                    <option value="Personal" <?php echo $editRequest && $editRequest['leave_type'] == 'Personal' ? 'selected' : ''; ?>>Personal</option>
                    <option value="Sick Leave" <?php echo $editRequest && $editRequest['leave_type'] == 'Sick Leave' ? 'selected' : ''; ?>>Sick Leave</option>
                    <option value="Planned Leave" <?php echo $editRequest && $editRequest['leave_type'] == 'Planned Leave' ? 'selected' : ''; ?>>Planned Leave</option>
                    <option value="Maternity Leave" <?php echo $editRequest && $editRequest['leave_type'] == 'Maternity Leave' ? 'selected' : ''; ?>>Maternity Leave</option>
                    <option value="Vacation Leave" <?php echo $editRequest && $editRequest['leave_type'] == 'Vacation Leave' ? 'selected' : ''; ?>>Vacation Leave</option>
                </select>

                <label for="leave_start_date">Leave Start Date:</label>
                <input type="date" name="leave_start_date" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['leave_start_date']) : ''; ?>">

                <label for="leave_end_date">Leave End Date:</label>
                <input type="date" name="leave_end_date" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['leave_end_date']) : ''; ?>">

                <label for="leave_reason">Reason for Leave:</label>
                <input type="text" name="leave_reason" placeholder="Reason for Leave" required value="<?php echo $editRequest ? htmlspecialchars($editRequest['leave_reason']) : ''; ?>">

                <label for="status">Status:</label>
                <select name="status" required>
                    <option value="">Select Status</option>
                    <option value="Pending" <?php echo $editRequest && $editRequest['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Approved" <?php echo $editRequest && $editRequest['status'] == 'Approved' ? 'selected' : ''; ?>>Approved</option>
                    <option value="Rejected" <?php echo $editRequest && $editRequest['status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                </select>

                <label for="active">Active/Inactive:</label>
                <select name="active" required>
                    <option value="Active" <?php echo $editRequest && $editRequest['active'] == 'Active' ? 'selected' : ''; ?>>Active</option>
                    <option value="Inactive" <?php echo $editRequest && $editRequest['active'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>

                <button type="submit" name="<?php echo $editRequest ? 'editRequest' : 'addRequest'; ?>"><?php echo $editRequest ? 'Save Changes' : 'Add Request'; ?></button>
                <button type="button" onclick="document.getElementById('request-modal').style.display = 'none';">Cancel</button>
                <?php if ($editRequest): ?>
                    <input type="hidden" name="recordIndex" value="<?php echo $editIndex; ?>">
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleDropdown(element) {
        const dropdown = element.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    }

    // Close dropdowns when clicking outside
    window.onclick = function(event) {
        if (!event.target.matches('.dropdown-toggle')) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(function(dropdown) {
                dropdown.style.display = 'none';
            });
        }
    };

    // Modal handling for adding and editing
    if (<?php echo $editRequest ? 'true' : 'false'; ?>) {
        document.getElementById('request-modal').style.display = 'flex';
    }

    function seeMore(index) {
        const request = <?php echo json_encode($_SESSION['leave_requests']); ?>[index];
        const modalContent = `
            <div style="background: white; padding: 20px; border-radius: 8px; width: 400px; max-width: 90%;">
                <h2>Leave Request Details for ${request.employee_name}</h2>
                <p><strong>Leave Type:</strong> ${request.leave_type}</p>
                <p><strong>Leave Dates:</strong> ${request.leave_start_date} to ${request.leave_end_date}</p>
                <p><strong>Reason:</strong> ${request.leave_reason}</p>
                <p><strong>Status:</strong> ${request.status}</p>
                <p><strong>Active/Inactive:</strong> ${request.active}</p>
                <button onclick="closeModal()">Close</button>
            </div>
        `;
        
        const seeMoreModal = document.createElement('div');
        seeMoreModal.id = 'see-more-modal';
        seeMoreModal.style.position = 'fixed';
        seeMoreModal.style.top = '0';
        seeMoreModal.style.left = '0';
        seeMoreModal.style.width = '100%';
        seeMoreModal.style.height = '100%';
        seeMoreModal.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        seeMoreModal.style.display = 'flex';
        seeMoreModal.style.justifyContent = 'center';
        seeMoreModal.style.alignItems = 'center';
        seeMoreModal.innerHTML = modalContent;
        
        document.body.appendChild(seeMoreModal);
    }

    function closeModal() {
        const modal = document.getElementById('see-more-modal');
        if (modal) {
            modal.remove(); // Remove the modal from the DOM
        }
    }
</script>

</body>
</html>