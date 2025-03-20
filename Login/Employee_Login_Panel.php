<?php 

 session_start(); // Starting session

 // Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve emp_ID from POST data
    $emp_ID = $_POST["emp_ID"];
    // Retrieve password from POST data
    $password = $_POST["password"];

    try {
        // Include the database connection file
        require_once("database.php");

        // Define SQL query to fetch user from 'users' table
        $query = "SELECT * FROM users WHERE emp_ID = ? AND password = ?";
        // Prepare SQL statement
        $stmt = $pdo->prepare(query: $query);

        // Bind parameters
        $stmt->bindParam(param: 1, var: $emp_ID);
        $stmt->bindParam(param: 2, var: $password);

        // Execute statement
        $stmt->execute();

        // Fetch user
        $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);

        // Check if user exists
        if ($user) {
            // User authenticated, set session and redirect
            $_SESSION["emp_ID"] = $emp_ID;
            header("Location: facescan.php"); // Redirect to dashboard.php
            exit(); // Terminate script execution after redirection
        } else {
            // Invalid credentials, redirect back to the login page
            header("Location: login.php"); // Redirect back to login.php
            exit(); // Terminate script execution after redirection
        }

        // Close statement
        $stmt = null;

        // Close database connection
        $pdo = null;

        // Terminate script execution
        die();
    } catch (PDOException $e) {
        // Handle PDOException and display error message
        die("Query Error: " . $e->getMessage());
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR & Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
  <style>
    body {
      background-color: rgba(205, 207, 203, 0.74);
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .container {
      display: flex;
      min-height: 100vh;
      position: relative;
      overflow: hidden;
    }

    .company-info,
    .login-panel {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      color: #218838;
    }

    .company-info {
      background-color: #fcfefe;
      border-bottom-right-radius: 20px;
      border-top-right-radius: 20px;
      padding: 40px;
    }

    .company-info h2 {
      font-size: 48px;
      font-weight: bold;
      color: #28a745;
      margin-bottom: 5px;
    }

    .company-info p {
      font-size: 25px;
      color: #218838;
    }

    .company-info .logo img {
      width: 600px;
      height: auto;
      margin-top: 20px;
    }

    .login-panel {
      background-color: rgba(205, 207, 203, 0.74);
      border-bottom-left-radius: 20px;
      border-top-left-radius: 20px;
      position: relative;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    .login-panel h1 {
      font-size: 32px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color:rgb(48, 163, 73);
    }

    .login-panel p {
      font-size: 25px; /* Increase the font size */
      margin-bottom: 20px;
      line-height: 1.6;
      color: #218838; /* Set text color to green */
    }

    /* Input Styles */
    .input-container {
      position: relative;
      width: 100%;
      margin-bottom: 20px;
    }

    .input-container input {
      width: 100%;
      padding: 15px 45px 15px 15px;
      border: 2px solid #218838;
      background-color: #f5f5f5;
      border-radius: 8px;
      outline: none;
      box-sizing: border-box;
      font-size: 16px;
      transition: border-color 0.3s ease;
    }

    .input-container input:focus {
      border-color: #28a745;
    }
    /* Hide arrows in number input (Chrome, Safari, Edge, Opera) */
  input[type="number"]::-webkit-outer-spin-button,
  input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
  }

  /* Hide arrows in Firefox */
  input[type="number"] {
      -moz-appearance: textfield;
  }

    /* Password Toggle Icon */
    .input-container i {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #aaa;
      cursor: pointer;
      font-size: 18px;
      transition: color 0.2s ease;
    }

    .input-container i:hover {
      color: #28a745;
    }


    /* Submit Button */
    .login-panel button {
      justify-content: center;
      width: 140px;
      background-color: #218838;
      color: white;
      padding: 20px;
      border: none;
      border-radius: 60px;
      cursor: pointer;
      font-size: 15px;
      transition: background-color 0.3s ease;
    }

    .login-panel button:hover {
      background-color: #1e7e34;
    }

    .login-footer a, .register-link a {
      color: #28a745;
      font-weight: bold;
      text-decoration: none;
      display: inline-block;
      margin-top: 10px;
    }

    .login-footer a:hover, .register-link a:hover {
      color: #1e7e34;
    }
    .company-info, .login-panel {
      width: 100%;
      border-radius: 0;
      }
  </style>
</head>
<body>

<div class="container">
  <div class="company-info">
    <h2><span>SUPERPACK</span> ENTERPRISE</h2>
    <p>Your Box matters</p>
    <div class="logo">
      <img src="/vinz/img/logo_front.png" alt="Company Logo">
    </div>
  </div>

  <div class="login-panel">
    <form method="post" action="login.php">
      <h1>Welcome back!</h1>
      <p>Please enter your details.</p>

      <!-- Employee ID Input -->
      <div class="input-container">
        <input id="Employee_ID" name="emp_ID" type="number" placeholder="Employee ID" required>
      </div>

      <!-- Password Input -->
      <div class="input-container">
        <input id="password" name="password" type="password" placeholder="Password" required>
        <i class="fas fa-eye" id="togglePassword"></i>
      </div>

      <a href="welcome.php"><button type="button">Attendance</button></a>
      <button type="submit">Login</button>
    </form>

    <div class="login-footer">
      <a href="forgetpassword.php">Forgot Password?</a>
    </div>
    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</div>

<script>
  const togglePassword = document.getElementById('togglePassword');
  const password = document.getElementById('password');

  togglePassword.addEventListener('click', () => {
    if (password.type === 'password') {
      password.type = 'text';
      togglePassword.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      password.type = 'password';
      togglePassword.classList.replace('fa-eye-slash', 'fa-eye');
    }
  });
</script>

</body>
</html>
