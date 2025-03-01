<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Superpack Enterprises - Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f5f5f5;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .container {
      width: 400px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
      padding: 40px;
      text-align: center;
    }

    .register-panel h1 {
      font-size: 32px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color:rgb(48, 163, 73);
    }

    .register-panel p {
      font-size: 18px;
      margin-bottom: 40px;
      color: #777;
    }

    .register-panel form {
      max-width: 400px;
      text-align: center;
    }

    .register-panel input[type="username"],
    .register-panel input[type="email"],
    .register-panel input[type="password"] {
      width: 100%;
      padding: 15px;
      margin-bottom: 20px;
      box-sizing: border-box;
      border: 2px solid #218838;
      background-color: #f5f5f5;
      outline: none;
      transition: border-color 0.3s ease;
    }

    .register-panel input[type="username"]:focus,
    .register-panel input[type="email"]:focus,
    .register-panel input[type="password"]:focus {
      border-color: #28a745;
    }

    .register-panel input[type="submit"] {
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

    .register-panel input[type="submit"]:hover {
      background-color: #1e7e34;
    }

    .login-links {
      margin-top: 20px
    }

    .login-links p {
      margin-top: 20px;
      font-size: 12px;
    }

    .login-links a {
      color: rgba(22, 188, 61, 0.98);
      text-decoration: none;
    }
  </style>
</head>
<body>
  <div class="container">
  <div class="register-panel">
    <h1>Registration</h1>
    <form>
      <input name="email" type="email" placeholder="Email" required>
      <input name="username" type="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <input type="submit" value="Register">
    </form>
    <div class="login-links">
      <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
  </div>
  </div>
  <script>
        document.querySelector('.register-btn').addEventListener('click', function() {
            window.location.href = 'Face_API/Python/register.php';
        });
    </script>
</body>
</html>
