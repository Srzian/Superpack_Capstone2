<?php
/* session_start(); // Starting session

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
        $stmt = $pdo->prepare($query);

        // Bind parameters
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $password);

        // Execute statement
        $stmt->execute();

        // Fetch user
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if user exists
        if ($user) {
            // User authenticated, set session and redirect
            $_SESSION["email"] = $email;
            header("Location: dashboard.php"); // Redirect to dashboard.php
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
    */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR & Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  </head>
  <style>
    body {
      background-color:rgba(47, 48, 50, 0.18);
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

    .right-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: left;
            margin-left: 64px;
            
    }

    .bottom-container {
            background-color: #64A651;
            font-size: 24px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            border-radius: 10px;
            color: #ffffff;
            padding-left: 24px;
            padding-top: 1px;
            padding-bottom: 1px;
            position:fixed;
            bottom: 0;
            display: none;
            z-index: 1000;
    }

    .company-info,
    .container-all {
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

    button {
            padding: 1px 15px;
            margin-top: 10px;
            font-size: 10px;
            cursor: pointer;
        }

        #capture-button {
            background-color: #64A651;
            color: #131313;
            border: 2px solid #131313;;
            border-radius: 1px;
            
            margin-top: 2px;
            font-size: 15px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
        }

        #back-button {
            background-color: #90EE93;
            color:  #131313;
            border: 2px solid #131313;;
            border-radius: 1px;
            margin-top: 2px;
            
            font-size: 15px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
        }
        
        #register-button {
            background-color: #90EE90;
            color: #131313;
            border: 2px solid #131313;;
            border-radius: 1px;
            margin-top: 2px;
            
            font-size: 15px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
        }

        input {
            font-size: 15px;
            font-family: 'Roboto', sans-serif;
            font-weight: bold;
            padding: 5px;
            background-color: transparent; /* Removes background */
            border: none; /* Removes all borders */
            border-bottom: 2px solid #000; /* Adds a bottom border */
            outline: none; /* Removes the default focus outline */
        }
        input::placeholder {
            color: #131313; /* Change this to your desired color */
        }
        #name {
            margin-bottom: 10px;
        }

        #capture-button:hover {
            background-color: #90EE90;
        }
        #back-button:hover {
            background-color: #90EE90;
        }
        #register-button:hover {
            background-color: #90EE90;
        }

        video {
            border: 1px solid #000000;
            border-radius: 4px;
            width: 600px;
            height: 600px;
            object-fit: cover;
        }

    .container-all h1 {
      font-size: 32px;
      margin-bottom: 20px;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color:rgb(48, 163, 73);
    }

    .container-all p {
      font-size: 14px;
      margin-bottom: 40px;
      color: black;
    }
    .container-all {
        height: 100vh;
        width: 100vw;
        background:rgba(205, 207, 203, 0.74) ;
        display: flex; /* Add flexbox to center content */
        justify-content: center; /* Center horizontally */
        align-items: center; /* Center vertically */
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
  <div class="container-all">
  <h1>Welcome back!</h1>
  <p>Please Look at the Camera.</p>
        <div class="right-container">

        <!-- Video element to display webcam stream -->
        <video id="webcam" autoplay></video>

            <div class="right-container">
                
              <input type="text" id="name" placeholder="Enter your name" required>
                
                <button id="register-button">Register</button>

                <button id="capture-button">Submit</button>

                <button id="back-button">Back</button>
            
            </div>

        <!-- Canvas element to capture and draw image -->
        <canvas id="canvas" style="display:none;"></canvas>

        </div>

        <div class="bottom-container">

            <!-- Text that changes to show the user the registration status -->
            <p id="status">Registration status: Waiting for capture...</p>

        </div>
</div>

<script>
        // Return to the welcome page when the back button is clicked
        document.getElementById('back-button').addEventListener('click', function() {
            window.location.href = '../../login.php';
        });

        // Redirect to the register page when the register button is clicked
        document.getElementById('register-button').addEventListener('click', function() {
            window.location.href = 'register.php';
        });

        // Get elements from the DOM
        const webcamElement = document.getElementById('webcam');
        const canvasElement = document.getElementById('canvas');
        const captureButton = document.getElementById('capture-button');
        const canvasContext = canvasElement.getContext('2d');

        // Initialize webcam stream
        function initWebcam() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    webcamElement.srcObject = stream;
                })
                .catch((error) => {
                    console.error("Error accessing webcam: ", error);
                });
        }

        // Capture image function
        function captureImage() {
            // Set canvas width and height to video element's width and height
            canvasElement.width = webcamElement.videoWidth;
            canvasElement.height = webcamElement.videoHeight;

            // Draw the current frame from the video to the canvas
            canvasContext.drawImage(webcamElement, 0, 0, canvasElement.width, canvasElement.height);
            
            // Convert the canvas to a base64-encoded PNG image
            const image = canvasElement.toDataURL('image/png');
            
            const name = document.getElementById('name').value;
            
            // Prepare the data payload to send to the Python script
            const dataPayload = { 
            image: image.split(',')[1], // Extract base64 string without the data URL prefix
            name: name,
            };
            
            // display the payload
            //console.log(dataPayload);

            // Send the image data to the Python script
            fetch('https://superpack-adu.com:5000/Face_API/mark-attendance', {  // Adjust the URL to your Python script's path
            method: 'POST',
            body: JSON.stringify(dataPayload),
            headers: {
                'Content-Type': 'application/json'
            }
            })
            .then((response) => response.json()) // Parse the JSON response
            .then((data) => { 
            console.log("Server response:", data); // Log the server response to the console 
            // if the response has success: true, then the face redirect to other page
            if (data.success) {
                console.log("Attendance Marked!");
                
                document.getElementById('status').textContent = data.message;

                document.querySelector('.bottom-container').style.backgroundColor = '#64A651';
                
                // make bottom container visible
                document.querySelector('.bottom-container').style.display = 'block';

                // Create a form element
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/../Capstone2/dashboardnew.php';  // PHP file that will set the session

                // Hidden input for username
                const inputName = document.createElement('input');
                inputName.type = 'hidden';
                inputName.name = 'username';
                inputName.value = data.name;
                form.appendChild(inputName);

                // Hidden input for role
                const inputRole = document.createElement('input');
                inputRole.type = 'hidden';
                inputRole.name = 'role';
                inputRole.value = data.role;
                form.appendChild(inputRole);

                // Hidden input for department
                const inputDepartment = document.createElement('input');
                inputDepartment.type = 'hidden';
                inputDepartment.name = 'user_department';
                inputDepartment.value = data.department;
                form.appendChild(inputDepartment);

                // Hidden input for loggedin variable
                const inputLoggedIn = document.createElement('input');
                inputLoggedIn.type = 'hidden';
                inputLoggedIn.name = 'loggedin';
                inputLoggedIn.value = true;
                form.appendChild(inputLoggedIn);

                // Append the form to the body
                document.body.appendChild(form);

                // Submit the form
                form.submit();

                
            } else {
                console.log("Error: ", data.error);
                
                document.getElementById('status').textContent = data.message;
                // make bottom container visible
                document.querySelector('.bottom-container').style.display = 'block';
                // make bottom container background color red
                document.querySelector('.bottom-container').style.backgroundColor = '#FF4C4C';

                // dissapear the bottom container after 3 seconds
                setTimeout(() => {
                document.querySelector('.bottom-container').style.display = 'none';
                }, 2500);
            }
            })
            .catch((error) => {
            console.error("Error sending image to server: ", error);

            
            document.getElementById('status').textContent = "Error sending image to server.";
            // make bottom container visible
            document.querySelector('.bottom-container').style.display = 'block';
            // make bottom container background color red
            document.querySelector('.bottom-container').style.backgroundColor = '#FF4C4C';

            // dissapear the bottom container after 3 seconds
            setTimeout(() => {
                document.querySelector('.bottom-container').style.display = 'none';
            }, 2500);
            });
        }

        // Initialize webcam on page load
        initWebcam();

        // Capture image when button is clicked
        captureButton.addEventListener('click', captureImage);
    </script>
</body>
</html>