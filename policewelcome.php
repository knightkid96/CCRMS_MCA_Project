<?php
$login_user = "";
if (isset($_GET['q'])) {
    $login_user = $_GET['q'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyber Crime Records Management System</title>
    <style>
        /* General styles for the body and header */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            margin: 0;
            padding: 0;
        }

        .header {
            background-color: #333;
            color: white;
            padding: 20px 0;
            text-align: center;
            font-size: 36px;
            font-weight: bold;
        }

        .welcome {
            font-size: 36px;
            margin-top: 20px;
            color: #2c3e50;
        }

        /* Updated container to use grid layout */
        .container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 equal columns */
            gap: 20px; /* Space between items */
            justify-items: center; /* Center items horizontally */
            margin-top: 50px;
            padding: 0 20px; /* Padding to avoid sticking to the edges */
        }

        .menu-item {
            text-align: center;
            transition: all 0.3s ease;
        }

        .menu-item div {
            background-size: cover;
            background-position: center;
            height: 120px;
            width: 120px;
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 10px;
        }

        .menu-item:hover div {
            transform: scale(1.1);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .menu-item p {
            font-size: 20px;
            font-weight: bold;
            color: rgb(0, 0, 0);
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

        .footer p {
            margin: 0;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        CYBER CRIME RECORDS MANAGEMENT SYSTEM
    </div>

    <!-- Welcome message -->
    <center>
        <h2 class="welcome">WELCOME POLICE <i><?php echo $login_user; ?></i></h2>
    </center>

    <!-- Main content with action buttons -->
    <div class="container">
        <a href="policeviewupdate.php?q=<?php echo $login_user; ?>" class="menu-item">
            <div style="background-image: url('images/view.png');"></div>
            <p>VIEW AND UPDATE MY DETAILS</p>
        </a>

        <a href="opencomplaint.php?q=<?php echo $login_user; ?>" class="menu-item">
            <div style="background-image: url('images/viewcomp.png');"></div>
            <p>VIEW ALL OPEN COMPLAINTS</p>
        </a>

        <a href="mycomplaints.php?q=<?php echo $login_user; ?>" class="menu-item">
            <div style="background-image: url('images/mycomp.png');"></div>
            <p>VIEW MY COMPLAINTS</p>
        </a>

        <a href="mainpage.html" class="menu-item">
            <div style="background-image: url('images/logout.png');"></div>
            <p>LOGOUT</p>
        </a>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
    </div>

</body>
</html>
