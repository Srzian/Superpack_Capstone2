<?php
 session_start(); // Starting session

 // Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Retrieve email from POST data
    $email = $_POST["email"];
    // Retrieve password from POST data
    $password = $_POST["password"];

    try {
        // Include the database connection file
        require_once("database.php");

        // Define SQL query to fetch user from 'users' table
        $query = "SELECT * FROM users WHERE email = ? AND password = ?";
        // Prepare SQL statement
        $stmt = $pdo->prepare(query: $query);

        // Bind parameters
        $stmt->bindParam(param: 1, var: $email);
        $stmt->bindParam(param: 2, var: $password);

        // Execute statement
        $stmt->execute();

        // Fetch user
        $user = $stmt->fetch(mode: PDO::FETCH_ASSOC);

        // Check if user exists
        if ($user) {
            // User authenticated, set session and redirect
            $_SESSION["email"] = $email;
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
      background-color:rgba(205, 207, 203, 0.74);
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
      color:rgb(250, 253, 252);
    }

    .company-info {
      background-color: rgb(252, 254, 254); /* Set background color with opacity */
      border-bottom-right-radius: 20px;
      border-top-right-radius: 20px;
      display: flex;
      align-items: center;
      padding: 40px;
    }

    .login-panel {
      background-color: 20px rgba(10, 1, 1, 0.99); /* Set background color with opacity */
      border-bottom-left-radius: 20px;
      border-top-left-radius: 10px;
      position: relative;
      z-index: 1;
      align-items: center;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
    }

    .company-info h2 {
      font-size: 48px; /* Increase the font size */
      font-weight: bold;
      margin-bottom: 5px;
      margin-top: 1px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2); /* Add text shadow */
      color: #28a745; /* Set text color to green */
    }

    .company-info p {
      font-size: 25px; /* Increase the font size */
      margin-bottom: 20px;
      line-height: 1.6;
      color: #218838; /* Set text color to green */
    }

    .company-info .logo img {
      width: 600px; /* Increase the width of the logo */
      height: auto;
      margin-right: 20px; /* Add margin to separate logo from text */
    }

    .login-panel h1 {
      font-size: 32px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color:rgb(48, 163, 73);
    }

    .login-panel p {
      font-size: 18px;
      margin-bottom: 40px;
      color: #777;
    }

    .login-panel form {
      max-width: 400px;
      text-align: center;
    }

    .login-panel input[type="email"],
    .login-panel input[type="password"] {
      width: 100%;
      padding: 15px;
      margin-bottom: 20px;
      box-sizing: border-box;
      border: 2px solid #218838;
      background-color: #f5f5f5;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .login-panel input[type="email"]:focus,
    .login-panel input[type="password"]:focus {
      border-color: #28a745;
    }

    .login-panel input[type="submit"] {
      width: 200px;
      background-color: #218838;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
      font-size: 18px;
      transition: background-color 0.3s ease;
    }

    .login-panel input[type="submit"]:hover {
      background-color: #1e7e34;
    }

    .login-footer a{
      margin-top: 20px;
      color: rgba(22, 188, 61, 0.98);
      font-weight: bold;
    }

    .register-link a {
      margin-top: 20px;
      color:rgba(22, 188, 61, 0.98);
      font-weight: bold;
    }

    @media only screen and (max-width: 768px) {
      .container {
        flex-direction: column;
      }

      .company-info,
      .login-panel {
        width: 100%;
        border-radius: 0;
      }
    }
  </style>

</head>
<body>

<div class="container">
  <div class="company-info">
    <div>
      <h2><span style="color: #28a745;">SUPERPACK</span> ENTERPRISE</h2>
      <center><p>Your Box matters</p></center>
    </div>
    <div class="logo">
      <img src="logo_front.png">
    </div>
  </div>
  <div class="login-panel">
    <form method="post" action="login.php">
      <h1>Welcome back!</h1>
      <p>Please enter your details.</p>
      <input name="email" type="email" placeholder="Email" required>
      <input name="password" type="password" placeholder="Password" required>
      <input type="submit" value="Sign In">
    </form>
    <div class="login-footer">
      <center><p><a href="forgetpassword.php">Forgot Password</a></p></center>
    </div>
    <!-- New div for registration link -->
    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register</a></p>
    </div>
  </div>
</div>

</body>
</html>