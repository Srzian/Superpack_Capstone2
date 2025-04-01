<?php
session_start();

// Database connection parameters
$host = 'localhost'; // Your database host
$db = 'employee_evaluations'; // Your database name
$user = 'root'; // Your database username
$pass = ''; // Leave this empty if you have no password

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle evaluation addition
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addEvaluation'])) {
    // Retrieve evaluation fields
    $employee_name = $_POST['employee_name'] ?? '';
    $position = $_POST['position'] ?? '';
    $evaluation_date = $_POST['evaluation_date'] ?? '';
    $evaluation_month = $_POST['evaluation_month'] ?? '';
    $evaluation_year = $_POST['evaluation_year'] ?? '';
    $evaluator_name = $_POST['evaluator_name'] ?? '';
    $remarks = $_POST['remarks'] ?? '';

    // Retrieve ratings for each evaluation question
    $work_quality = $_POST['work_quality'] ?? '';
    $efficiency_productivity = $_POST['efficiency_productivity'] ?? '';
    $communication_skills = $_POST['communication_skills'] ?? '';
    $teamwork_collaboration = $_POST['teamwork_collaboration'] ?? '';
    $initiative_problem_solving = $_POST['initiative_problem_solving'] ?? '';
    $adaptability_flexibility = $_POST['adaptability_flexibility'] ?? '';
    $reliability_accountability = $_POST['reliability_accountability'] ?? '';
    $attendance_punctuality = $_POST['attendance_punctuality'] ?? '';
    $professionalism_work_ethic = $_POST['professionalism_work_ethic'] ?? '';
    $overall_performance = $_POST['overall_performance'] ?? '';

    // Create the evaluation date string
    $full_evaluation_date = "$evaluation_year-$evaluation_month-$evaluation_date";

    // Insert into the database
    $stmt = $conn->prepare("INSERT INTO evaluations (employee_name, position, evaluation_date, evaluator_name, remarks, work_quality, efficiency_productivity, communication_skills, teamwork_collaboration, initiative_problem_solving, adaptability_flexibility, reliability_accountability, attendance_punctuality, professionalism_work_ethic, overall_performance) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssssssss", $employee_name, $position, $full_evaluation_date, $evaluator_name, $remarks, $work_quality, $efficiency_productivity, $communication_skills, $teamwork_collaboration, $initiative_problem_solving, $adaptability_flexibility, $reliability_accountability, $attendance_punctuality, $professionalism_work_ethic, $overall_performance);
    $stmt->execute();
    $stmt->close();

    // Redirect to the same page to refresh the evaluations list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle evaluation deletion
if (isset($_GET['delete'])) {
    $recordIndex = (int)$_GET['delete']; // Ensure the index is an integer
    $stmt = $conn->prepare("DELETE FROM evaluations WHERE id = ?");
    $stmt->bind_param("i", $recordIndex);
    $stmt->execute();
    $stmt->close();
    header('Location: ' . $_SERVER['PHP_SELF']); // Refresh the page
    exit;
}

// Fetch evaluations from the database
$result = $conn->query("SELECT * FROM evaluations");
$evaluations = $result->fetch_all(MYSQLI_ASSOC);

// Get unique years for the filter
$years = array_unique(array_map(function($evaluation) {
    return date('Y', strtotime($evaluation['evaluation_date']));
}, $evaluations));
$years = array_values($years); // Re-index the array

// Filter evaluations by search keyword and year
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
$yearFilter = isset($_POST['year_filter']) ? $_POST['year_filter'] : '';

// Filter evaluations based on search and year
$filtered_evaluations = array_filter($evaluations, function ($evaluation) use ($searchKeyword, $yearFilter) {
    $matchesSearch = !$searchKeyword || stripos($evaluation['employee_name'], $searchKeyword) !== false;
    $matchesYear = !$yearFilter || date('Y', strtotime($evaluation['evaluation_date'])) == $yearFilter;
    return $matchesSearch && $matchesYear;
});

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Evaluation Form</title>
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

        h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
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

        .add-record-btn {
            padding: 15px 25px;
            background-color: #4a90e2;
            color: white;
            font-size: 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px; /* Add some space below the button */
        }

        .add-record-btn:hover {
            background-color: #357ab7;
        }

        .modal {
            display: flex;
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
            width: 800px; /* Increased width for a more rectangular shape */
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

        .question {
            margin-bottom: 20px;
        }

        .question label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .stars {
            display: flex;
            gap: 5px;
        }

        .star {
            font-size: 24px;
            color: #ccc;
            cursor: pointer;
        }

        .star.selected {
            color: #f39c12; /* Color for selected stars */
        }

        .evaluation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .evaluation-table th, .evaluation-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .evaluation-table th {
            background-color: #3e4a72; /* Header background color */
            color: white; /* Header text color */
        }

        .evaluation-table tr {
            background-color: #fff; /* Default row background color */
        }

        .evaluation-table tr:hover {
            background-color: #f2f2f2; /* Row hover effect */
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons button {
            padding: 5px 10px;
            background-color: #4a90e2;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .action-buttons button:hover {
            background-color: #357ab7;
        }

        .delete-button {
            background-color: #e74c3c; /* Red color for delete button */
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #c0392b; /* Darker red on hover */
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
            <ul class="dropdown" style="display: none; background-color: #3e4a72; color: white;">
                <li><a href="personnel.php" style="color: white;"><i class="fas fa-id-badge"></i> Personnel Records</a></li>
                <li><a href="leave.php" style="color: white;"><i class="fas fa-plane"></i> Leave Request</a></li>
                <li><a href="evaluation.php" style="color: white;"><i class="fas fa-star"></i> Evaluation Form</a></li>
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
    <h1>Employee Evaluation Form</h1>
    <button class="add-record-btn" onclick="document.getElementById('evaluation-modal').style.display = 'flex';">Add New Evaluation</button>

    <!-- Filters -->
    <div class="filters">
        <form method="POST" style="display: flex; align-items: center;">
            <input type="text" name="search" placeholder="Search by Employee Name" value="<?php echo htmlspecialchars($searchKeyword); ?>">
            <select name="year_filter">
                <option value="">Select Year</option>
                <?php foreach ($years as $year): ?>
                    <option value="<?php echo $year; ?>" <?php echo $year == $yearFilter ? 'selected' : ''; ?>><?php echo $year; ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Filter</button>
        </form>
    </div>

    <!-- Modal for Adding Evaluation -->
    <div id="evaluation-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <h2>Add Evaluation</h2>
            <form method="POST">
                <label for="employee_name">Employee Name:</label>
                <input type="text" name="employee_name" placeholder="Enter Employee Name" required>

                <label for="position">Position:</label>
                <input type="text" name="position" placeholder="Enter Position" required>

                <label for="evaluation_date">Evaluation Date:</label>
                <div style="display: flex; gap: 10px;">
                    <input type="number" name="evaluation_date" placeholder="Day" min="1" max="31" required>
                    <input type="number" name="evaluation_month" placeholder="Month" min="1" max="12" required>
                    <input type="number" name="evaluation_year" placeholder="Year" min="2000" max="2100" required>
                </div>
                <label for="evaluator_name">Evaluator's Name:</label>
                <input type="text" name="evaluator_name" placeholder="name" required>

                <!-- Evaluation Questions -->
                <div class="question">
                    <label> Work Quality – How well does the employee produce accurate and high-quality work?</label>
                    <div class="stars" id="work_quality">
                        <span class="star" data-value="1">★</span>
                        < span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="work_quality" id="work_quality_input" value="">
                </div>

                <div class="question">
                    <label> Efficiency & Productivity – How effectively does the employee manage their time and resources?</label>
                    <div class="stars" id="efficiency_productivity">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="efficiency_productivity" id="efficiency_productivity_input" value="">
                </div>

                <div class="question">
                    <label> Communication Skills – How well does the employee communicate with others?</label>
                    <div class="stars" id="communication_skills">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="communication_skills" id="communication_skills_input" value="">
                </div>

                <div class="question">
                    <label> Teamwork & Collaboration – How well does the employee work with others?</label>
                    <div class="stars" id="teamwork_collaboration">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="teamwork_collaboration" id="teamwork_collaboration_input" value="">
                </div>

                <div class="question">
                    <label> Initiative & Problem Solving – How well does the employee take initiative and solve problems?</label>
                    <div class="stars" id="initiative_problem_solving">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span ```php
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="initiative_problem_solving" id="initiative_problem_solving_input" value="">
                </div>

                <div class="question">
                    <label> Adaptability & Flexibility – How well does the employee adapt to changes and new situations?</label>
                    <div class="stars" id="adaptability_flexibility">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="adaptability_flexibility" id="adaptability_flexibility_input" value="">
                </div>

                <div class="question">
                    <label> Reliability & Accountability – How reliable and accountable is the employee?</label>
                    <div class="stars" id="reliability_accountability">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="reliability_accountability" id="reliability_accountability_input" value="">
                </div>

                <div class="question">
                    <label> Attendance & Punctuality – How well does the employee adhere to attendance and punctuality?</label>
                    <div class="stars" id="attendance_punctuality">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="attendance_punctuality" id="attendance_punctuality_input" value="">
                </div>

                <div class="question">
                    <label> Professionalism & Work Ethic – How professional is the employee in their work ethic?</label>
                    <div class="stars" id="professionalism_work_ethic">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="professionalism_work_ethic" id=" professionalism_work_ethic_input" value="">
                </div>

                <div class="question">
                    <label> Overall Performance – How would you rate the overall performance of the employee?</label>
                    <div class="stars" id="overall_performance">
                        <span class="star" data-value="1">★</span>
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                    </div>
                    <input type="hidden" name="overall_performance" id="overall_performance_input" value="">
                </div>

                <label for="remarks">Remarks:</label>
                <textarea name="remarks" placeholder="Enter any additional remarks here..."></textarea>

                <button type="submit">Submit Evaluation</button>
            </form>
            <button onclick="document.getElementById('evaluation-modal').style.display = 'none';">Close</button>
        </div>
    </div>

    <!-- Evaluation Table -->
    <table class="evaluation-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Evaluation Date</th>
                <th>Evaluator Name</th>
                <th>Remarks</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_evaluations as $evaluation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evaluation['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['position']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['evaluation_date']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['evaluator_name']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['remarks']); ?></td>
                    <td class="action-buttons">
                        <button class="print-button" onclick="printEvaluation(<?php echo $evaluation['id']; ?>)">Print</button>
                        <button class="delete-button" onclick="if(confirm('Are you sure you want to delete this evaluation?')) { window.location.href='?delete=<?php echo $evaluation['id']; ?>'; }">Delete</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Star rating functionality
    document.querySelectorAll('.stars').forEach(starContainer => {
        const stars = starContainer.querySelectorAll('.star');
        stars.forEach(star => {
            star.addEventListener('click', () => {
                const value = star.getAttribute('data-value');
                stars.forEach(s => s.classList.remove('selected'));
                for (let i = 0; i < value; i++) {
                    stars[i].classList.add('selected');
                }
                const inputId = starContainer.id + '_input';
                document.getElementById(inputId).value = value;
            });
        });
    });

    // Dropdown toggle for sidebar
    function toggleDropdown(element) {
        const dropdown = element.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
    }

    // Print evaluation function
    function printEvaluation(id) {
        const evaluation = <?php echo json_encode($filtered_evaluations); ?>.find(e => e.id == id);
        const starCount = (question) => {
            return '★'.repeat(parseInt(evaluation[question])) + '☆'.repeat(10 - parseInt(evaluation[question]));
        };

        const printWindow = window.open('', '', 'height=600,width=800');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print Evaluation</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .logo { text-align: center; margin-bottom: 20px; }
                </style>
            </head>
            <body>
                <div class="logo">
                    <img src="LOGO.png " alt="Company Logo" style="width: 150px;">
                </div>
                <h2>Employee Evaluation</h2>
                <table>
                    <tr>
                        <th>Employee Name</th>
                        <td>${evaluation.employee_name}</td>
                    </tr>
                    <tr>
                        <th>Position</th>
                        <td>${evaluation.position}</td>
                    </tr>
                    <tr>
                        <th>Evaluation Date</th>
                        <td>${evaluation.evaluation_date}</td>
                    </tr>
                    <tr>
                        <th>Evaluator Name</th>
                        <td>${evaluation.evaluator_name}</td>
                    </tr>
                    <tr>
                        <th>Remarks</th>
                        <td>${evaluation.remarks}</td>
                    </tr>
                    <tr>
                        <th>Work Quality</th>
                        <td>${starCount('work_quality')}</td>
                    </tr>
                    <tr>
                        <th>Efficiency & Productivity</th>
                        <td>${starCount('efficiency_productivity')}</td>
                    </tr>
                    <tr>
                        <th>Communication Skills</th>
                        <td>${starCount('communication_skills')}</td>
                    </tr>
                    <tr>
                        <th>Teamwork & Collaboration</th>
                        <td>${starCount('teamwork_collaboration')}</td>
                    </tr>
                    <tr>
                        <th>Initiative & Problem Solving</th>
                        <td>${starCount('initiative_problem_solving')}</td>
                    </tr>
                    <tr>
                        <th>Adaptability & Flexibility</th>
                        <td>${starCount('adaptability_flexibility')}</td>
                    </tr>
                    <tr>
                        <th>Reliability & Accountability</th>
                        <td>${starCount('reliability_accountability')}</td>
                    </tr>
                    <tr>
                        <th>Attendance & Punctuality</th>
                        <td>${starCount('attendance_punctuality')}</td>
                    </tr>
                    <tr>
                        <th>Professionalism & Work Ethic</th>
                        <td>${starCount('professionalism_work_ethic')}</td>
                    </tr>
                    <tr>
                        <th>Overall Performance</th>
                        <td>${starCount('overall_performance')}</td>
                    </tr>
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
