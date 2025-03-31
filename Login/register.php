<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data with proper validation
    $emp_ID = $_POST["emp_ID"] ?? '';
    $name = $_POST["name"] ?? '';
    $email = $_POST["email"] ?? '';
    $role = $_POST["role"] ?? '';
    $department = $_POST["department"] ?? '';  // Fixed variable name
    $password = password_hash($_POST["password"] ?? '', PASSWORD_DEFAULT);
    $image = $_POST["image"] ?? null;  // Handle case where image isn't set
   
    try {
        require_once("database.php");

        // Then include it in your query:
$query = "INSERT INTO register (emp_ID, name, email, role, department, password, image) 
          VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt->execute([$emp_ID, $name, $email, $role, $department, $password, $image]);
        // Redirect after successful registration
        header("Location: facescan.php");
        exit();
    } catch (PDOException $e) {
        die("Query Error: " . $e->getMessage());
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New User Registration</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Reset margins and paddings */
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            font-family: Arial, sans-serif;
            background-color: rgba(205, 207, 203, 0.74);
        }

        .container {
            display: flex;
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            display: flex;

        }
        .left-container {
          flex: 1;
            justify-content: center; align-items:normal;
            background-color: #fcfefe;
            border-bottom-right-radius: 20px;
            border-top-right-radius: 20px;
            padding: 40px;
        }

        .left-container h2 {
            font-size: 48px;
            font-weight: bold;
            color: #28a745;
            margin-bottom: 5px;
            text-align: center;
        }

        .left-container p {
            font-size: 25px;
            color: #218838;
            width: 400px;
            height: auto;
            margin-top: 1px;
        }

        .container .left-container img {
            width: 120px;
            height: auto;
            justify-content: center;
            margin-top: 5px;
        }

        .webcam-container {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #fcfefe;
            padding: 20px;
            margin-bottom: 0% ;
        }

        .webcam-container video {
            width: 600px; /* Larger webcam feed */
            height: 450px;
            border: 2px solid #218838;
            border-radius: 10px;
            object-fit: cover;
        }

        .form-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            gap: 20px;
            background-color: rgba(205, 207, 203, 0.74);
        }

        .form-container h1 {
            font-size: 32px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            color: rgb(48, 163, 73);
        }

        .form-container p {
            font-size: 25px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #218838;
        }

        .input-container {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        .input-container input, .input-container select {
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

        .input-container input:focus, .input-container select:focus {
            border-color: #28a745;
        }

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
        .button-group {
      display: flex;
      flex-direction: column;
      width: 100%;
      gap: 0.7rem;

    }
    /* Tablet and larger - side by side buttons */
    @media (min-width: 768px) {
      .button-group {
        flex-direction: row;
        gap: 0.5rem;
      }
    }


    .button-group {
    display: flex;
    flex-direction: column;
    gap: 20px; /* Add space between buttons */
    width: 100%;
}

/* Tablet and larger screens - align buttons side by side */
@media (min-width: 768px) {
    .button-group {
        flex-direction: row;
        gap: 10px;
    }
}

.form-container button {
    justify-content: center;
    font-family: 'Roboto', sans-serif;
    width: 100%; /* Full width buttons */
    background-color: #218838;
    color: white;
    padding: 0.75rem;
    border: none;
    border-radius: 60px;
    cursor: pointer;
    font-size: 1rem;
    font-weight: bold;
    border: 2px solid #131313;
    transition: background-color 0.3s ease;
}

.form-container button:hover {
    background-color: #1e7e34;
}

#capture-button {   
    background-color: #64A651;
    color: #131313;
}

#register-button {
    background-color: #90EE90;
    color: #131313;
}

button:active {
    transform: scale(0.98);
}


        .register-footer a {
            color: #28a745;
            font-weight: bold;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .register-footer a:hover {
            color: #1e7e34;
        }

        canvas {
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
    <div class="left-container">
            <h2><span>SUPERPACK</span> ENTERPRISE</h2>
            <br>
            <center><p>Your Box matters</p>
                <img src="/vinz/img/logo_front.png" alt="Company Logo"></center>
                <br>
                <br>
        <!-- Webcam Container -->
            <center><video id="webcam" autoplay></video>
            <canvas id="canvas" style="display:none;"></canvas></center>
        </div>

        <!-- Form Container -->
        <div class="form-container">
            <form method="post" action="register.php" id="registrationForm">
                <h1>Register New User</h1>
                <p>Please enter your details.</p>

                <!-- Employee ID Input -->
                <div class="input-container">
                    <input id="emp_ID" name="emp_ID" type="number" placeholder="Employee ID" required>
                </div>

                <!-- Full Name Input -->
                <div class="input-container">
                    <input id="name" name="name" type="text" placeholder="Full Name" required>
                </div>

                <!-- Last Name Input -->
                <div class="input-container">
                    <input id="email" name="email" type="email" placeholder="Email Address" required>
                </div>
                
                <!-- Role Selection -->
        <div class="input-container">
            <select id="role" name="role" required>
                <option value="" disabled selected>Select Position</option>
                <option value="Manager">Manager</option>
                <option value="Employee">Employee</option>
            </select>
        </div>  
                <!-- Department Selection -->
        <div class="input-container">
            <select id="department" name="department" required>
                <option value="" disabled selected>Select Department</option>
                <option value="sales">Sales</option>
                <option value="purchasing">Purchasing</option>
                <option value="proddev">Product Development</option>
                <option value="warehouse">Warehouse</option>
                <option value="logistics">Logistics</option>
                <option value="accounting">Accounting</option>
            </select>
        </div>

        
                <!-- Password Input -->
                <div class="input-container">
                    <input id="password" name="password" type="password" placeholder="Password" required>
                    <i class="fas fa-eye" id="togglePassword"></i>
                </div>

                <div class="button-group">
    <button type="button" id="capture-button">Capture Image</button>
    <button type="submit" id="register-button">Register</button>
</div>
                
                
            </form>

            <div class="register-footer">
                <p>Already have an account? <a href="facescan.php">Login</a></p>
            </div>
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

    const webcamElement = document.getElementById('webcam');
    const canvasElement = document.getElementById('canvas');
    const captureButton = document.getElementById('capture-button');
    const canvasContext = canvasElement.getContext('2d');
    let isWebcamActive = false;

    // Initialize Webcam
    function initWebcam() {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then((stream) => {
                webcamElement.srcObject = stream;
                isWebcamActive = true;
            })
            .catch((error) => {
                console.error("Error accessing webcam: ", error);
                alert("Unable to access webcam. Please allow permission.");
            });
    }

    // Update the captureImage function to use the correct field name
function captureImage() {
    if (!isWebcamActive) {
        alert("Webcam is not active. Please ensure your webcam is connected and allowed.");
        return;
    }

    canvasElement.width = webcamElement.videoWidth;
    canvasElement.height = webcamElement.videoHeight;
    canvasContext.drawImage(webcamElement, 0, 0, canvasElement.width, canvasElement.height);

    const image = canvasElement.toDataURL('image/png');
    const name = document.getElementById('name').value; // Fixed to use 'name' instead of 'full_name'
    
    // Add hidden input for the image to the form
    let imageInput = document.querySelector('input[name="image"]');
    if (!imageInput) {
        imageInput = document.createElement('input');
        imageInput.type = 'hidden';
        imageInput.name = 'image';
        document.getElementById('registrationForm').appendChild(imageInput);
    }
    imageInput.value = image;
    
    alert("Image captured successfully!");
}


        // Add hidden input for the image to the form
    const imageInput = document.createElement('input');
    imageInput.type = 'hidden';
    imageInput.name = 'image';
    imageInput.value = image;
    document.getElementById('registrationForm').appendChild(imageInput);

    alert("Image captured successfully!");

    // Submit the form
    document.getElementById('registrationForm').submit();


        
            // Send the image data to the Python script
            fetch('https://localhost:5000/Face_API/register', {  // Adjust the URL to your Python script's path
                method: 'POST',
                body: JSON.stringify(dataPayload),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then((response) => response.json()) // Parse the JSON response
            .then((data) => { 
                console.log("Server response:", data); // Log the server response to the console 
                if (data.success) {
                    document.getElementById('status').textContent = data.message;

                    
                    document.querySelector('.bottom-container').style.backgroundColor = '#64A651';
                    
                    // make bottom container visible
                    document.querySelector('.bottom-container').style.display = 'block';

                    // Redirect to index.php after successful registration
                    setTimeout(() => { // Delay the redirect for 1 second
                        window.location.href = 'login.php';
                    }, 2500);
                } else {
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
            });
        

        

        
    

    // Start Webcam on Page Load
    initWebcam();

    // Attach Event Listener to Capture Button
    captureButton.addEventListener('click', captureImage);
</script>
</body>
</html>
