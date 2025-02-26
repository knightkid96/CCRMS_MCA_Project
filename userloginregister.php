<?php
// Define variables and set to empty values
$usernameErr = "";
$username = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $usernameErr = "Please enter username/password";
    } else {
        $username = test_input($_POST["username"]);
    }

    if (empty($_POST["password"])) {
        $usernameErr = "Please enter username/password";
    } 
}

if (!empty($_POST["username"]) && !empty($_POST["password"])) {
    $servername= "localhost";
    $usernamed = "root";
    $password = "";
    $dbname = "cybercrimedatabase";

    $conn = new mysqli($servername, $usernamed, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $uname=$_POST["username"];
    $pass=$_POST["password"];
    $sql = "SELECT * FROM user WHERE username = '$uname'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row["password"] == $pass) {
            $_SESSION['user_name'] = $uname;
            header("Location: userwelcome.php?q=$uname");
        } else {
            $usernameErr = "Incorrect credentials! Please enter again.";
        }
    } else {
        $usernameErr = "No user found with this username.";
    }
    
    if($row["password"]==$pass){
        $_SESSION['user_name']= $uname; 
        header("Location: userwelcome.php?q=$uname"); 
    }
    else {
        $usernameErr="Incorrect credentials!!! Please enter again.";
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
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cyber Crime Records Management System</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(to right, #2c3e50, #3498db);
                margin: 0;
                padding: 0;
            }

            .header {
                background-color: #2c3e50;
                color: white;
                text-align: center;
                padding: 30px 0;
                font-size: 36px;
                font-weight: bold;
            }

            .content {
                max-width: 800px;
                margin: 80px auto;
                background-color: white;
                border-radius: 8px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                padding: 30px;
                text-align: center;
            }

            .content h2 {
                color: #2c3e50;
                font-size: 24px;
                margin-bottom: 20px;
            }

            .form-input {
                width: 100%;
                padding: 12px;
                margin: 10px 0;
                border-radius: 4px;
                border: 1px solid #ddd;
                font-size: 16px;
            }

            .form-submit {
                width: 100%;
                padding: 14px;
                background-color: #2980b9;
                color: white;
                border: none;
                font-size: 18px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .form-submit:hover {
                background-color: #3498db;
            }

            .form-link {
                display: block;
                margin-top: 10px;
                font-size: 16px;
                color: #2980b9;
                text-decoration: none;
            }

            .form-link:hover {
                text-decoration: underline;
            }

            .error {
                color: red;
                font-size: 14px;
            }

            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: #2c3e50;
                color: white;
                text-align: center;
                padding: 10px 0;
            }

            .back-link {
                color: white;
                text-decoration: none;
                font-size: 16px;
                display: block;
                text-align: center;
                margin-top: 20px;
            }

            .back-link:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="header">
            CYBER CRIME RECORDS MANAGEMENT SYSTEM
        </div>

        <div class="content">
            <h2>Login to Your Account</h2>
            <form method="POST" action="#">
                <input type="text" name="username" class="form-input" placeholder="Enter Your Username" value="<?php echo $username; ?>" required>
                <input type="password" name="password" class="form-input" placeholder="Enter Your Password" required>
                <span class="error"><?php echo $usernameErr; ?></span>
                <input type="submit" name="submit" value="Submit" class="form-submit">
                <a href="userreg.php" class="form-link">New User? Register here!</a>
            </form>
        </div>

        <a href="mainpage.html" class="back-link">Back to Main Page</a>

        <div class="footer">
            <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
        </div>
    </body>
</html>
