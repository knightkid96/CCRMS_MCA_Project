<?php
$login_user="";
if(isset($_GET['q'])) {
    $login_user = $_GET['q'];
}

$opencomplaints = $errorMsg = "";

$servername= "localhost";
$usernamed = "root";
$passwords = "";
$dbname = "cybercrimedatabase";

$conn = new mysqli($servername, $usernamed, $passwords, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
 }

$sql = "SELECT * FROM complaint WHERE status IN ('NEW','INPROGRESS','VERIFICATION')  ORDER BY datetime";
$result=$conn->query($sql);
if ($result->num_rows > 0) {
	$opencomplaints = "<table border='1' style='width: 100%; margin-top: 20px; border-collapse: collapse;'>
	<tr style='background-color: #3498db; color: white;'>
		<th>Complaint ID</th>
		<th>Category</th>
		<th>Category Description</th>
		<th>Bureau Location</th>
		<th>Subject</th>
		<th>Details</th>
		<th>URL</th>
		<th>Date</th>
		<th>Crime Location</th>
		<th>Social Media</th>
		<th>Suspect Details</th>
		<th>Status</th>
		<th>Priority</th>
		<th>Bureau Notes</th>
	</tr>";
	while($row = $result->fetch_assoc()) {
        $spec = $row['category'];
        $sql2 = "SELECT * FROM specializations WHERE specialization = '$spec'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
    
        // Check if $row2 is not null
        if ($row2) {
            $categoryDescription = $row2["s_desc"];
            $categoryLocation = $row2["s_location"];
        } else {
            $categoryDescription = "No description available";
            $categoryLocation = "Location not found";
        }
    
        $opencomplaints .= "<tr style='background-color: #ecf0f1;'>
            <td>" . $row['c_id'] . "</td>
            <td>" . $row['category'] . "</td>
            <td>" . $categoryDescription . "</td>
            <td>" . $categoryLocation . "</td>
            <td>" . $row['subject'] . "</td>
            <td>" . $row['details'] . "</td>
            <td>" . $row['url'] . "</td>
            <td>" . $row['datetime'] . "</td>
            <td>" . $row['area'] . "</td>
            <td>" . $row['social_media'] . "</td>
            <td>" . $row['suspect'] . "</td>
            <td>" . $row["status"] . "</td>
            <td>" . $row['priority'] . "</td>
            <td>" . $row['bureau_notes'] . "</td>
        </tr>";
    }
	$opencomplaints .= "</table>";
} else {
	$errorMsg = "No Open Complaints in the system at this point!!!";
}
$conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Cyber Crime Records Management System</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background: linear-gradient(to right, #2c3e50, #3498db);
                color: white;
                margin: 0;
                padding: 0;
            }

            h1, h2 {
                font-weight: bold;
                color: white;
            }

            h1 {
                text-align: center;
                background-color: #333;
                padding: 20px;
                margin: 0;
            }

            .container {
                margin: 40px auto;
                width: 100%;
                max-width: 1600px; /* Ensure the container is wide enough for large tables */
                background-color: white;
                border-radius: 10px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                padding: 0; /* Removed extra padding to allow space for the table */
                color: black;
            }

            /* Scrollable wrapper for the table */
            .table-wrapper {
                overflow-x: auto; /* Enables horizontal scrolling */
                margin-bottom: 20px; /* Gives space below the table and keeps the scrollbar on top */
            }

            table {
                width: 100%;
                border-collapse: collapse;
                background-color: white;
            }

            th, td {
                padding: 15px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            th {
                background-color: #3498db;
                color: white;
            }

            tr:nth-child(even) {
                background-color: #ecf0f1;
            }

            .footer {
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                background-color: #333;
                color: white;
                text-align: center;
                padding: 10px 0;
            }

            a {
                color: #3498db;
                font-weight: bold;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }

            .error {
                color: #FF0000;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <h1>CYBER CRIME RECORDS MANAGEMENT SYSTEM</h1>
		<div><a href="policewelcome.php?q=<?php echo $login_user;?>">Back</a></div>
        <div class="container">
            <h2>All Open Complaints</h2>
			 <!-- Table wrapper with horizontal scrollbar -->
			 <div class="table-wrapper">
                <div id="opencomplaints"><?php echo $opencomplaints;?></div>
            </div>
        </div>
            <div><a href="policewelcome.php?q=<?php echo $login_user;?>">Back</a></div>
            <br>
            <div><span class="error"><?php echo $errorMsg;?></span></div>
           

        <div class="footer">
            <p>&copy;Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
        </div>
    </body>
</html>
