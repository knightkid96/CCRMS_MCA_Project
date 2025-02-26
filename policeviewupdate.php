<?php
$login_user="";
if(isset($_GET['q'])) {
    $login_user = $_GET['q'];
}
$addressErr = $phoneErr = $specErr = "";
$name = $username = $address = $spec = $gender = $phone = "";
$successMsg = $errorMsg = "";

// DB Connection
$servername = "localhost";
$usernamed = "root";
$passwords = "";
$dbname = "cybercrimedatabase";

$conn = new mysqli($servername, $usernamed, $passwords, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login_user = test_input($login_user);
$sql = "SELECT * from police where police_id='$login_user'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

$name = $row["name"];
$username = $row["police_id"];
$address = $row["address"];
$spec = $row["specialization"];
$phone = $row["phone"];
$gender = $row["gender"];

$conn->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {  
  if (empty($_POST["address"])) {
    $addressErr = "Address is required";
  } else {
    $address = test_input($_POST["address"]);
  }
  
  if (empty($_POST["phone"])) {
    $phoneErr = "Phone number is required";
  } else {
    $phone = test_input($_POST["phone"]);
    if (!preg_match("/^[0-9]*$/", $phone)) {
      $phoneErr = "Only numbers allowed"; 
    }
  }
  
  if (isset($_POST['specialization']) && $_POST['specialization'] == '0') { 
    $specErr = "Specialization is required";
  } else {
    $spec = test_input($_POST["specialization"]);
  }
}

if (!empty($_POST["address"]) && !empty($_POST["phone"]) && isset($_POST['specialization']) && $_POST['specialization'] != '0' && $phoneErr == "")
{	
    $conn = new mysqli($servername, $usernamed, $passwords, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "UPDATE police SET address = '$address', phone = '$phone', specialization = '$spec' WHERE police_id = '$login_user'";
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
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #2c3e50, #3498db);
            margin: 0;
            padding: 0;
        }

        h1, h2 {
            color: white;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            background-color: #333;
            padding: 20px;
        }

        .container {
            margin: 50px auto;
            width: 80%;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        table {
            width: 100%;
            margin: 20px 0;
            border-spacing: 15px;
        }

        td {
            padding: 8px;
            font-size: 18px;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus, select:focus {
            border-color: #3498db;
            outline: none;
        }

        .error {
            color: #FF0000;
            font-size: 14px;
        }

        .success {
            color: #008000;
            font-size: 14px;
        }

        input[type="submit"], input[type="button"] {
            background-color: #3498db;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 16px;
        }

        input[type="submit"]:hover, input[type="button"]:hover {
            background-color: #2980b9;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: gray;
            color: white;
            text-align: center;
            padding: 10px 0;
        }

    </style>
</head>
<body>
    <h1>CYBER CRIME RECORDS MANAGEMENT SYSTEM</h1>

    <div class="container">
        <h2>View and Update My Details</h2>
        <div><a href="policewelcome.php?q=<?php echo $login_user;?>">Back</a></div>
        <form method="post" action="#">
            <table>
                <tr>
                    <td>Username/Police ID:</td>
                    <td><?php echo $username; ?></td>
                </tr>
                <tr>
                    <td>Name:</td>
                    <td><?php echo $name; ?></td>
                </tr>
                <tr>
                    <td>Gender:</td>
                    <td><?php echo $gender; ?></td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td><input type="text" name="address" value="<?php echo $address; ?>"><span class="error">* <?php echo $addressErr;?></span></td>
                </tr>
                <tr>
                    <td>Phone No:</td>
                    <td><input type="text" name="phone" value="<?php echo $phone; ?>"><span class="error">* <?php echo $phoneErr;?></span></td>
                </tr>
                <tr>
                    <td>Specialization:</td>
                    <td>
                        <select name="specialization">
                            <option value="0">Please Select</option>
                            <option>Bank Account Fraud</option>
                            <option>Cyberbullying</option>
                            <option>Child Pornography</option>
                            <option>Identity Theft</option>
                            <option>Social Media Crime</option>
                            <option>Hacking and Viruses</option>
                            <option>E-Commerce Scam</option>
                            <option>Email or Phone Call Scam</option>
                        </select>
                        <span class="error">* <?php echo $specErr;?></span>
                    </td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" value="Update"></td>
                    <td><input type="button" value="Back" onclick="location.href='policewelcome.php?q=<?php echo $login_user; ?>';"></td>
                </tr>
            </table>
        </form>
        <br>
        <span class="success"><?php echo $successMsg;?></span><br>
        <span class="error"><?php echo $errorMsg;?></span>
    </div>

    <div class="footer">
        <p>&copy;Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
    </div>
</body>
</html>
