<?php
session_start();

// Initialize evaluations in session if not already set
if (!isset($_SESSION['evaluations'])) {
    $_SESSION['evaluations'] = [];
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

    // Add the new evaluation to the session array
    $_SESSION['evaluations'][] = [
        'employee_name' => $employee_name,
        'position' => $position,
        'evaluation_date' => "$evaluation_year-$evaluation_month-$evaluation_date",
        'evaluator_name' => $evaluator_name,
        'remarks' => $remarks,
        'ratings' => [
            'work_quality' => $work_quality,
            'efficiency_productivity' => $efficiency_productivity,
            'communication_skills' => $communication_skills,
            'teamwork_collaboration' => $teamwork_collaboration,
            'initiative_problem_solving' => $initiative_problem_solving,
            'adaptability_flexibility' => $adaptability_flexibility,
            'reliability_accountability' => $reliability_accountability,
            'attendance_punctuality' => $attendance_punctuality,
            'professionalism_work_ethic' => $professionalism_work_ethic,
            'overall_performance' => $overall_performance,
        ],
    ];

    // Redirect to the same page to refresh the evaluations list
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Handle evaluation deletion
if (isset($_GET['delete'])) {
    $recordIndex = (int)$_GET['delete']; // Ensure the index is an integer
    if (isset($_SESSION['evaluations'][$recordIndex])) {
        unset($_SESSION['evaluations'][$recordIndex]); // Remove the record
        $_SESSION['evaluations'] = array_values($_SESSION['evaluations']); // Re-index the array
    }
    header('Location: ' . $_SERVER['PHP_SELF']); // Refresh the page
    exit;
}

// Get unique years for the filter
$years = array_unique(array_map(function($evaluation) {
    return date('Y', strtotime($evaluation['evaluation_date']));
}, $_SESSION['evaluations']));
$years = array_values($years); // Re-index the array

// Filter evaluations by search keyword and year
$searchKeyword = isset($_POST['search']) ? $_POST['search'] : '';
$yearFilter = isset($_POST['year_filter']) ? $_POST['year_filter'] : '';

// Filter evaluations based on search and year
$filtered_evaluations = array_filter($_SESSION['evaluations'], function ($evaluation) use ($searchKeyword, $yearFilter) {
    $matchesSearch = !$searchKeyword || stripos($evaluation['employee_name'], $searchKeyword) !== false;
    $matchesYear = !$yearFilter || date('Y', strtotime($evaluation['evaluation_date'])) == $yearFilter;
    return $matchesSearch && $matchesYear;
});
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
                        <span class="star" data-value="2">★</span>
                        <span class="star" data-value="3">★</span>
                        <span class="star" data-value="4">★</span>
                        <span class="star" data-value="5">★</span>
                        <span class="star" data-value="6">★</span>
                        <span class="star" data-value="7">★</span>
                        <span class="star" data-value="8">★</span>
                        <span class="star" data-value="9">★</span>
                        <span class="star" data-value="10">★</span>
                        <input type="hidden" name="work_quality" id="work_quality_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Efficiency & Productivity – How effectively does the employee complete tasks within deadlines?</label>
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
                        <input type="hidden" name="efficiency_productivity" id="efficiency_productivity_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Communication Skills – How clearly and professionally does the employee communicate with others?</label>
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
                        <input type="hidden" name="communication_skills" id="communication_skills_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Teamwork & Collaboration – How well does the employee work with colleagues and contribute to team success?</label>
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
                        <input type="hidden" name="teamwork_collaboration" id="teamwork_collaboration_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Initiative & Problem-Solving – How often does the employee take the initiative to solve problems and improve work processes?</label>
                    <div class="stars" id="initiative_problem_solving">
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
                        <input type="hidden" name="initiative_problem_solving" id="initiative_problem_solving_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Adaptability & Flexibility – How well does the employee handle changes in responsibilities or work conditions?</label>
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
                        <input type="hidden" name="adaptability_flexibility" id="adaptability_flexibility_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Reliability & Accountability – How dependable is the employee in completing assigned tasks and meeting expectations?</label>
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
                        <input type="hidden" name="reliability_accountability" id="reliability_accountability_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Attendance & Punctuality – How consistent is the employee in arriving on time and maintaining good attendance?</label>
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
                        <input type="hidden" name="attendance_punctuality" id="attendance_punctuality_input" required>
                    </div>
                </div>
                <div class="question">
                    <label> Professionalism & Work Ethic – How well does the employee demonstrate professionalism and dedication to their work?</label>
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
                        <input type="hidden" name="professionalism_work_ethic" id="professionalism_work_ethic_input" required>
                    </div>
                </div>

                <label for="remarks">Remarks:</label>
                <textarea name="remarks" placeholder="Add comments here..." rows="4"></textarea>

                <button type="submit" name="addEvaluation" class="add-record-btn">Add Evaluation</button>
                <button type="button" onclick="document.getElementById('evaluation-modal').style.display = 'none';">Cancel</button>
            </form>
        </div>
    </div>

    <!-- Evaluations Table -->
    <table class="evaluation-table">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Position</th>
                <th>Evaluation Date</th>
                <th>Evaluator Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($filtered_evaluations as $index => $evaluation): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evaluation['employee_name']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['position']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['evaluation_date']); ?></td>
                    <td><?php echo htmlspecialchars($evaluation['evaluator_name']); ?></td>
                    <td class="action-buttons">
                        <button onclick="seeMoreEvaluation(<?php echo htmlspecialchars(json_encode($evaluation)); ?>)">See More</button>
                        <a href="?delete=<?php echo $index; ?>" onclick="return confirm('Are you sure you want to delete this evaluation?');" class="delete-button">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script>
    // Function to handle star rating selection
    document.querySelectorAll('.stars').forEach(starContainer => {
        starContainer.addEventListener('click', (event) => {
            if (event.target.classList.contains('star')) {
                const rating = event.target.getAttribute('data-value');
                const inputId = starContainer.id + '_input';
                document.getElementById(inputId).value = rating;

                // Remove selected class from all stars
                starContainer.querySelectorAll('.star').forEach(star => {
                    star.classList.remove('selected');
                });

                // Add selected class to the clicked star and all previous stars
                for (let i = 1; i <= rating; i++) {
                    starContainer.querySelector(`.star[data-value="${i}"]`).classList.add('selected');
                }
            }
        });
    });

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

    function seeMoreEvaluation(evaluation) {
        // Create a modal to display evaluation details
        const modalContent = `
            <div style="background: white; padding: 20px; border-radius: 8px; width: 600px; max-width: 90%;">
                <h2>Evaluation Details for ${evaluation.employee_name}</h2>
                <p><strong>Position:</strong> ${evaluation.position}</p>
                <p><strong>Evaluation Date:</strong> ${evaluation.evaluation_date}</p>
                <p><strong>Evaluator name:</strong> ${evaluation.evaluator_name}</p>
                <h3>Ratings:</h3>
                <p>Work Quality: ${evaluation.ratings.work_quality} ⭐</p>
                <p>Efficiency & Productivity: ${evaluation.ratings.efficiency_productivity} ⭐</p>
                <p>Communication Skills: ${evaluation.ratings.communication_skills} ⭐</p>
                <p>Teamwork & Collaboration: ${evaluation.ratings.teamwork_collaboration} ⭐</p>
                <p>Initiative & Problem-Solving: ${evaluation.ratings.initiative_problem_solving} ⭐</p>
                <p>Adaptability & Flexibility: ${evaluation.ratings.adaptability_flexibility} ⭐</p>
                <p>Reliability & Accountability: ${evaluation.ratings.reliability_accountability} ⭐</p>
                <p>Attendance & Punctuality: ${evaluation.ratings.attendance_punctuality} ⭐</p>
                <p>Professionalism & Work Ethic: ${evaluation.ratings.professionalism_work_ethic} ⭐</p>
                <p>Overall Performance: ${evaluation.ratings.overall_performance} ⭐</p>
                <h3>Remarks:</h3>
                <p>${evaluation.remarks}</p>
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