<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superpack Enterprise Admin Dashboard</title>
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

        /* Sidebar Styling */
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

        /* Sidebar logo */
        .sidebar .logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar .logo img {
            width: 120px; /* Adjust size as needed */
            height: auto;
        }

        /* Sidebar links */
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
            background-color: #4a90e2; /* Bright neon blue */
            transform: translateX(5px);
        }

        .sidebar ul li .arrow {
            transition: transform 0.3s ease;
        }

        .rotate {
            transform: rotate(180deg);
        }

        /* Improved Dropdown Menu Styling */
        .sidebar .dropdown {
            display: none;
            flex-direction: column;
            padding-left: 30px; /* Space out the dropdown items */
            margin-top: 5px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        .sidebar .dropdown.open {
            display: flex;
        }

        .sidebar .dropdown li {
            padding: 12px 20px;
            font-size: 15px;
            border-bottom: 1px solid #3e4a72;
            background-color: #364f6b;
            margin-bottom: 6px; /* Adds spacing between items */
            border-radius: 4px;
        }

        .sidebar .dropdown li:hover {
            background-color: #4a90e2;
        }

        /* Toggle Button */
        .toggle-btn {
            font-size: 30px;
            cursor: pointer;
            color: #fff;
            position: absolute;
            top: 20px;
            left: 20px;
        }

        /* Add icons to the menu */
        .sidebar ul li i {
            margin-right: 15px;
        }

        /* Main Content */
        .content {
            margin-left: 250px;
            padding: 30px;
            width: calc(100% - 250px); /* Ensure content area takes up remaining space */
            transition: margin-left 0.3s ease;
        }

        /* Style for content when sidebar is toggled */
        .content.expanded {
            margin-left: 0;
            width: 100%;
        }

        /* Task Management Section */
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

        /* Filters Section */
        .filters {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filters select, .filters input {
            padding: 10px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }

        /* Add New Task Button */
        .add-task-btn {
            padding: 10px 20px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            display: inline-block;
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
            <li><a href="#">Dashboard</a></li>
            <li><a href="#">Payroll</a></li>
            <li class="menu-item">
                <a href="javascript:void(0)">Employee Management <i class="fas fa-chevron-down arrow"></i></a>
                <ul class="dropdown">
                    <li><a href="#">Personnel Records</a></li>
                    <li><a href="#">Leave Request</a></li>
                    <li><a href="#">Evaluation Form</a></li>
                </ul>
            </li>
            <li><a href="#">Attendance</a></li>
            <li><a href="#">Settings</a></li>
            <li><a href="#">About Company</a></li>
            <li><a href="#">Logout</a></li>
        </ul>
    </div>

       

</body>
</html>
