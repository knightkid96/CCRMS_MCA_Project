<?php
$login_user="";
if(isset($_GET['q'])) {
    $login_user = $_GET['q'];
}
$addressErr = $pincodeErr = $emailErr = "";
$name = $username = $address = $pincode = $email = $gender = $phone = "";
$successMsg = $errorMsg = "";

//DB Connection
$servername= "localhost";
$usernamed = "root";
$passwords = "";
$dbname = "cybercrimedatabase";

$conn = new mysqli($servername, $usernamed, $passwords, $dbname);
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

$login_user = test_input($login_user);
$sql = "SELECT * from user where username='$login_user'";
$result=$conn->query($sql);
$row=$result->fetch_assoc();

$name=$row["name"];
$username=$row["username"];
$address=$row["address"];
$pincode=$row["pincode"];
$email=$row["email"];
$phone=$row["phone"];
$gender=$row["gender"];
$aadharnumber=$row["aadharnumber"];

$conn->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
  if (empty($_POST["address"])) {
    $addressErr = "Address is required";
  } else {
    $address = test_input($_POST["address"]);
  }
  
  if (empty($_POST["pincode"])) {
    $pincodeErr = "Pincode is required";
  } else {
    $pincode = test_input($_POST["pincode"]);
    // check if pincode only contains letters and whitespace
    if (!preg_match("/^[0-9]*$/",$pincode)) {
      $pincodeErr = "Only numbers allowed"; 
    }
  }
  
  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
  } else {
    $email = test_input($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format"; 
    }
  }
  
  if (empty($_POST["phone"])) {
    $phone = "";
  } else {
    $phone = test_input($_POST["phone"]);
  }

}

if (!empty($_POST["address"]) && !empty($_POST["pincode"]) && !empty($_POST["email"]) && $emailErr == "" && $pincodeErr == "")
{	
    $conn = new mysqli($servername, $usernamed, $passwords, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
     }
    
    $sql = "UPDATE user SET address = '$address', pincode = '$pincode', email = '$email', phone = '$phone' WHERE username = '$login_user'";
    if ($conn->query($sql) === TRUE) {
    $successMsg = "Update Successful. Click on BACK button.";
    } 
	else {
    $errorMsg = "Update Failed due to some Internal Error!!! Please try again.";
    }
	$conn->close();
}


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
	/* General body styles */
  body {
                font-family: Arial, sans-serif;
                background: linear-gradient(to right, #2c3e50, #3498db);
                margin: 0;
                padding: 0;
            }

/* Header styles */
div[style="background-color: black;"] {
    padding: 20px;
    text-align: center;
}

h1 {
    color: #fff;
    font-size: 45px;
    margin: 0;
    font-weight: bold;
}

/* Form Container styles */
div[style="margin-top: 10px;height: 400px;width: 800px;margin-right: 470px;background-image: url('cover22.jpg');background-size: 800px;margin-top: 80px;"] {
    background-size: cover;
    background-position: center;
    border-radius: 10px;
    padding: 30px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    margin-top: 80px;
}

/* Heading for the form */
h2 {
    text-align: center;
    font-size: 36px;
    font-weight: bold;
    color:rgb(0, 0, 0);
}

/* Table styles for the form */
table {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
    background-color: rgba(255, 255, 255, 0.8);
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}

td {
    padding: 10px;
    font-size: 16px;
    color: #333;
}

input[type="text"], input[type="submit"], input[type="button"] {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 16px;
    background-color: #fff;
    box-sizing: border-box;
}

input[type="submit"], input[type="button"] {
    background-color: #2980b9;
    color: white;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover, input[type="button"]:hover {
    background-color: #3498db;
}

/* Error and Success message styles */
.error {
    color: #e74c3c;
}

.success {
    color: #27ae60;
}

/* Footer styles */
.footer {
    position: fixed;
    left: 0;
    bottom: 0;
    width: 100%;
    background-color: #333;
    color: white;
    text-align: center;
    padding: 10px 0;
    font-size: 14px;
}

.footer p {
    margin: 0;
}
		</style>
    </head>
    <body>
        <div style="background-color: black;">
             <h1 style="margin-left:50px;font-size: 50px; color:whitesmoke;font:bold;">CYBER CRIME RECORDS MANAGEMENT SYSTEM</h1>  
        </div>
        
        <h2 style="margin-left:0px;font:bold;font-size:45px;"> View and Update My Details</h2>
        <div style="margin-top: 10px;height: 400px; width: 800px; margin-right:470px; background-image: url('cover22.jpg'); background-size: 800px; margin-top: 80px;"> 
		<center>
		    <form method="post" action="#">
            <table border="6.0" style="margin-left:650px; height:200px;width:600px;color:black;font:white;"> 
		        <tr><td >&nbsp;&nbsp;Username:</td><td>&nbsp;&nbsp;&nbsp; <?php echo $username; ?></td></tr>
            <tr><td >&nbsp;&nbsp;Aadhar Number:</td><td>&nbsp;&nbsp;&nbsp; <?php echo $aadharnumber; ?></td></tr>
				<tr><td >&nbsp;&nbsp;Name:</td><td>&nbsp;&nbsp;&nbsp; <?php echo $name; ?></td></tr>
				<tr><td >&nbsp;&nbsp;Gender:</td><td>&nbsp;&nbsp;&nbsp; <?php echo $gender; ?></td></tr>
				<tr><td >&nbsp;&nbsp;Address:</td><td >&nbsp;&nbsp;&nbsp; <input type="text" name="address" value="<?php echo $address; ?>"><span class="error">* <?php echo $addressErr;?></span></td></tr>
			    <tr><td >&nbsp;&nbsp;Pincode:</td><td >&nbsp;&nbsp;&nbsp; <input type="text" name="pincode" value="<?php echo $pincode;?>"><span class="error">* <?php echo $pincodeErr;?></span></td></tr>
				<tr><td >&nbsp;&nbsp;Email ID:</td><td >&nbsp;&nbsp;&nbsp; <input type="text" name="email" value="<?php echo $email;?>"><span class="error">* <?php echo $emailErr;?></span></td></tr>
			    <tr><td >&nbsp;&nbsp;Phone No:</td><td >&nbsp;&nbsp;&nbsp; <input type="text" name="phone" value="<?php echo $phone;?>"></td></tr>
				<tr><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;<input type="submit" name="submit" value="Update"></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Back" onclick="location.href='userwelcome.php?q=<?php echo $login_user; ?>';" style="width:65px;"><br><span class="success"><?php echo $successMsg;?></span><span class="error"><?php echo $errorMsg;?></span></td></tr>
             </table>
			 </form>
        <center>
	    </div>
	<div class="footer" style="position: fixed; left: 0; bottom: 0; width: 100%; background-color: gray; color: white; text-align: center;">
  <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
	</div>
    </body>
</html>
