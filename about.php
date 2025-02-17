<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Superpack Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
        }
        .content {
            margin-left: 250px;
            padding: 40px;
            width: calc(100% - 250px);
        }
        .container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .box {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .box h2 {
            color: #2e3a59;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .box p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }
        .highlight {
            color: #4a90e2;
            font-weight: bold;
        }
        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
        }
    </style>
</head>
<body>
    <!-- Sidebar Include -->
    <?php include 'sidebar.php'; ?>
    
    <!-- Main Content -->
    <div class="content">
        <div class="container">
            <div class="box">
                <h2>Company History</h2>
                <p>SUPERPACK ENTERPRISES (SP) has been established as a box-packaging enterprise that aims to provide its customers the proverbial peace of mind, with the knowledge that they are getting their money’s worth because the packaging of their products is well-taken care of, safely ensconced against external forces that could bring about uncalled for damage. It is located in 1557/8008 Meycauayan Industrial Subdivision, Stonehills Drive, Phase1, Pantoc Maycauyan City Bulacan while its satellite office is located in 1473 G. Masangkay St. Empire Plaza Condominium Tondo Manila, Philippines. It was founded by March 2015. Superpack Ent product line are as follows: * Regular Slotted Containers (RSC) * Special Designed Boxes</p>
            </div>
            
            <div class="box">
                <h2>Basic Information</h2>
                <p><span class="highlight">Location:</span> 557/8008 Meycauayan Industrial Sub Stonehills Drive Phase 1 Brgy, Meycauayan, 3020 Bulacan</p>
                <p><span class="highlight">Phone:</span>  (02) 8475 7465</p>
                <p><span class="highlight">Email:</span> contact@superpack.com</p>
            </div>
            
            <div class="box">
                <h2>Find Us on Map</h2>
                <div class="map-container">
                    <iframe width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen 
                    src="https://www.openstreetmap.org/export/embed.html?bbox=120.9654,14.7571,120.9684,14.7591&layer=mapnik&marker=14.7581,120.9669"></iframe>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
