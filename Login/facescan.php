<?php
session_start(); 

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HR & Management System</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    
    body {
      background-color: rgba(47, 48, 50, 0.18);
      font-family: Arial, sans-serif;
      overflow-x: hidden;
    }

    .container {
      display: flex;
      min-height: 100vh;
      width: 100%;
      flex-direction: column;
    }

    /* Tablet and desktop layout */
    @media (min-width: 768px) {
      .container {
        flex-direction: row;
      }
    }

    .company-info,
    .container-all {
      width: 100%;
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
    }

    .company-info {
      background-color: rgb(252, 254, 254);
      padding: 2rem 1rem;
    }

    @media (min-width: 768px) {
      .company-info {
        flex: 1;
        border-bottom-right-radius: 20px;
        border-top-right-radius: 20px;
      }
      
      .container-all {
        flex: 1;
      }
    }

    .company-info h2 {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color: #28a745;
      text-align: center;
    }

    .company-info p {
      font-size: 1.2rem;
      margin-bottom: 1rem;
      line-height: 1.6;
      color: #218838;
      text-align: center;
    }

    .logo {
      width: 100%;
      display: flex;
      justify-content: center;
      margin: 1rem 0;
    }

    .company-info .logo img {
      width: 100%;
      max-width: 280px;
      height: auto;
    }

    /* Adjust sizes for larger screens */
    @media (min-width: 768px) {
      .company-info h2 {
        font-size: 2.5rem;
      }
      
      .company-info p {
        font-size: 1.5rem;
      }
      
      .company-info .logo img {
        max-width: 350px;
      }
    }

    @media (min-width: 1024px) {
      .company-info h2 {
        font-size: 3rem;
      }
      
      .company-info .logo img {
        max-width: 450px;
      }
    }

    .container-all {
      background: rgba(205, 207, 203, 0.74);
      min-height: 60vh;
      padding-bottom: 3rem;
    }

    .container-all h1 {
      font-size: 1.8rem;
      margin-bottom: 0.5rem;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      color: rgb(48, 163, 73);
      text-align: center;
    }

    .container-all p {
      font-size: 1rem;
      margin-bottom: 1.5rem;
      color: black;
      text-align: center;
    }

    .right-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      width: 100%;
    }

    /* Video container */
    .video-container {
      width: 100%;
      max-width: 350px;
      margin-bottom: 1.5rem;
    }

    video {
      width: 100%;
      aspect-ratio: 1/1;
      object-fit: cover;
      border: 1px solid #000000;
      border-radius: 8px;
    }

    @media (min-width: 768px) {
      .video-container {
        max-width: 400px;
      }
    }

    @media (min-width: 1024px) {
      .video-container {
        max-width: 500px;
      }
    }

    /* Form controls */
    .controls {
      display: flex;
      flex-direction: column;
      width: 100%;
      max-width: 350px;
      align-items: center;
    }

    input {
      width: 100%;
      font-size: 1rem;
      font-family: 'Roboto', sans-serif;
      font-weight: bold;
      padding: 0.75rem 0.5rem;
      margin-bottom: 1.2rem;
      background-color: transparent;
      border: none;
      border-bottom: 2px solid #000;
      outline: none;
    }

    input::placeholder {
      color: #131313;
    }

    .button-group {
      display: flex;
      flex-direction: column;
      width: 100%;
      gap: 0.7rem;

    }

    button {
      width: 100%;
      padding: 0.75rem;
      font-size: 1rem;
      font-family: 'Roboto', sans-serif;
      font-weight: bold;
      border: 2px solid #131313;
      border-radius: 60px;
      cursor: pointer;
      transition: background-color 0.3s, transform 0.2s;
    }

    button:active {
      transform: scale(0.98);
    }

    #capture-button {
      background-color: #64A651;
      color: #131313;
    }

    #back-button {
      background-color: #90EE93;
      color: #131313;
    }
    
    #register-button {
      background-color: #90EE90;
      color: #131313;
    }

    #capture-button:hover, #back-button:hover, #register-button:hover {
      background-color: #90EE90;
    }

    /* Tablet and larger - side by side buttons */
    @media (min-width: 768px) {
      .button-group {
        flex-direction: row;
        gap: 0.5rem;
      }
    }

    /* Status message container */
    .bottom-container {
      background-color: #64A651;
      position: fixed;
      bottom: 1rem;
      left: 50%;
      transform: translateX(-50%);
      width: 90%;
      max-width: 350px;
      padding: 0.75rem 1rem;
      border-radius: 10px;
      text-align: center;
      font-size: 1rem;
      font-family: 'Roboto', sans-serif;
      font-weight: bold;
      color: #ffffff;
      display: none;
      z-index: 1000;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    @media (min-width: 768px) {
      .bottom-container {
        max-width: 400px;
        font-size: 1.1rem;
      }
    }

    /* Hide canvas but keep it accessible */
    canvas {
      display: none;
    }
  </style>
</head>
<body>

<div class="container">
  <!-- Company info section -->
  <div class="company-info">
    <div>
      <h2><span style="color: #28a745;">SUPERPACK</span> ENTERPRISE</h2>
      <p>Your Box matters</p>
    </div>
    <div class="logo">
      <img src="/vinz/img/logo_front.png" alt="Superpack Enterprise Logo">
    </div>
  </div>
  
  <!-- Authentication section -->
  <div class="container-all">
    <h1>Welcome back!</h1>
    <p>Please tap your ID.</p>
    
    <div class="right-container">
      <!-- Video container -->
      <div class="video-container">
        <video id="webcam" autoplay></video>
      </div>
      
      <!-- Form controls -->
      <div class="controls">
        <input type="text" id="name" placeholder="Enter your name" required>
        
        <div class="button-group">
          <button id="register-button"><a href="register.php"></a>Register</button>
          <button id="capture-button">Submit</button>
          <button id="back-button">Back</button>
        </div>
      </div>
      
      <!-- Hidden canvas for capturing images -->
      <canvas id="canvas"></canvas>
    </div>
  </div>
  
  <!-- Status message container -->
  <div class="bottom-container">
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

  /// Initialize webcam stream
  function initWebcam() {
            navigator.mediaDevices.getUserMedia({ video: true })
                .then((stream) => {
                    webcamElement.srcObject = stream;
                })
                .catch((error) => {
                    console.error("Error accessing webcam: ", error);
                });
        }

    // Check if device is mobile to set appropriate constraints
    const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    
    const constraints = {
      video: {
        facingMode: isMobile ? 'user' : 'user',
        width: { ideal: 640 },
        height: { ideal: 640 }
      }
    };
    
    navigator.mediaDevices.getUserMedia(constraints)
      .then((stream) => {
        webcamElement.srcObject = stream;
      })
      .catch((error) => {
        console.error("Error accessing webcam: ", error);
        document.getElementById('status').textContent = "Cannot access camera. Please allow camera permissions.";
        document.querySelector('.bottom-container').style.backgroundColor = '#FF4C4C';
        document.querySelector('.bottom-container').style.display = 'block';
        
        setTimeout(() => {
          document.querySelector('.bottom-container').style.display = 'none';
        }, 3000);
      });
      
      


</script>
</body>
</html>
