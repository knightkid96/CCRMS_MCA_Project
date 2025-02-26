<?php
$login_user="";
if(isset($_GET['q'])) {
    $login_user = $_GET['q'];
}

$mycomplaints = $errorMsg = $spec = "";

$servername= "localhost";
$usernamed = "root";
$passwords = "";
$dbname = "cybercrimedatabase";

$conn = new mysqli($servername, $usernamed, $passwords, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
 }

$sql2 = "SELECT * FROM police WHERE police_id = '$login_user'";
$result2=$conn->query($sql2);
$row2=$result2->fetch_assoc();
$spec=$row2["specialization"];
$spec=test_input($spec);

$sql = "SELECT * FROM complaint WHERE category = '$spec'";
$result=$conn->query($sql);
if ($result->num_rows > 0) {
	$mycomplaints = "<table border='1' style='width: 100%; border-collapse: collapse; margin-top: 20px;'>
	<tr style='background-color: #3498db; color: white;'>
		<th>Complaint ID</th>
		<th>Category</th>
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
		<th>Action</th>
	</tr>";
	while($row = $result->fetch_assoc()) 
	{
		$compid=$row['c_id'];
		$linkc = "<a href='viewandupdatecomplaint.php?q=$login_user&c=$compid' style='color: #3498db; font-weight: bold;'>View and Update</a>";
		$mycomplaints = $mycomplaints . "<tr style='background-color: #ecf0f1;'>
		<td>" . $row['c_id'] . "</td>
		<td>" . $row['category'] . "</td>
		<td>" . $row['subject'] . "</td>
		<td>" . $row['details'] . "</td>
		<td>". $row['url'] . "</td>
		<td>" . $row['datetime'] . "</td>
		<td>". $row['area'] . "</td>
		<td>" . $row['social_media'] . "</td>
		<td>" . $row['suspect'] . "</td>
		<td>" . $row["status"] . "</td>
		<td>" . $row['priority'] . "</td>
		<td>" . $row['bureau_notes'] . "</td>
		<td>" . $linkc ."</td>
		</tr>";
	}
	$mycomplaints = $mycomplaints . "</table>";
} else {
	$errorMsg = "No $spec category complaints in the system at this point!!!";
}
$conn->close();

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
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
                max-width: 1200px;
                background-color: white;
                border-radius: 10px;
                box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
                padding: 30px;
                color: black;
            }
            
            .table-wrapper {
                overflow-x: auto;
                margin-bottom: 20px;
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
            
            a {
                color: #3498db;
                font-weight: bold;
                text-decoration: none;
            }
            
            a:hover {
                text-decoration: underline;
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
            
            .error {
                color: #FF0000;
                font-size: 14px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>CYBER CRIME RECORDS MANAGEMENT SYSTEM</h1>
            <div><a href="policewelcome.php?q=<?php echo $login_user;?>">Back</a></div>
            <h2 style="text-align: center;"><i><?php echo $spec; ?></i>&nbsp;&nbsp;Complaints</h2>
            <div><span class="error"><?php echo $errorMsg;?></span></div>
            <div class="table-wrapper">
                <div id="mycomplaints"><?php echo $mycomplaints;?></div>
            </div>
        </div>
        
        <div class="footer">
            <p>&copy;Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
        </div>
    </body>
</html>
