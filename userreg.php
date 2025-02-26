<?php
// Define variables and set to empty values
$nameErr = $usernameErr = $passwordErr = $addressErr = $pincodeErr = $emailErr = $genderErr = $aadharErr = "";
$name = $username = $password = $address = $pincode = $email = $gender = $phone = $aadharnumber = "";
$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed"; 
        }
    }
    
    if (empty($_POST["username"])) {
        $usernameErr = "Username is required";
    } else {
        $username = test_input($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $username)) {
            $usernameErr = "Only letters, numbers, and white space allowed"; 
        }
    }
    
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $password)) {
            $passwordErr = "Only letters, numbers, and white space allowed"; 
        }
    }
    
    if (empty($_POST["address"])) {
        $addressErr = "Address is required";
    } else {
        $address = test_input($_POST["address"]);
    }
    
    if (empty($_POST["pincode"])) {
        $pincodeErr = "Pincode is required";
    } else {
        $pincode = test_input($_POST["pincode"]);
        if (!preg_match("/^[0-9]{6}$/", $pincode)) {
            $pincodeErr = "Pincode must be a 6-digit number"; 
        }
    }
    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format"; 
        }
    }
    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }
    
    if (empty($_POST["phone"])) {
        $phone = "";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[0-9]{10}$/", $phone)) {
            $phoneErr = "Phone number must be a 10-digit number";
        }
    }

    if (empty($_POST["aadharnumber"])) {
        $aadharErr = "Aadhaar number is required";
    } else {
        $aadharnumber = test_input($_POST["aadharnumber"]);
        if (!preg_match("/^[0-9]{12}$/", $aadharnumber)) {
            $aadharErr = "Aadhaar number must be a 12-digit number";
        }
    }
}

// Check if form submission is valid and all fields are correctly filled
if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["name"]) && !empty($_POST["address"]) && !empty($_POST["pincode"]) && !empty($_POST["email"]) && !empty($_POST["gender"]) && !empty($_POST["aadharnumber"]) && $nameErr == "" && $usernameErr == "" && $passwordErr == "" && $pincodeErr == "" && $emailErr == "" && $aadharErr == "") {

    $servername = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbname = "cybercrimedatabase";
    
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Check if username already exists (Prepared Statement)
    $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errorMsg = "Username already taken. Please choose a different username.";
    } else {
        // Insert new user using Prepared Statement
        $stmt = $conn->prepare("INSERT INTO user (username, password, name, address, pincode, email, phone, gender, aadharnumber) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $username, $password, $name, $address, $pincode, $email, $phone, $gender, $aadharnumber);
        
        if ($stmt->execute()) {
            echo "<script>
                    alert('Registration Successful, redirecting you to the login page.');
                    window.location.href = 'userloginregister.php';
                  </script>";
        }
    }

    $stmt->close();
    $conn->close();
}

// Function to sanitize input data
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
          font-family: Arial, sans-serif;
                background: linear-gradient(to right, #2c3e50, #3498db);
                margin: 0;
                padding: 0;
        }
        h1, h2 {
            text-align: center;
            font-size: 35px;
            margin-top: 20px;
        }
        .form-container {
            width: 60%;
            margin: 0 auto;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
        }
        .form-container input[type="text"], .form-container input[type="password"], .form-container input[type="email"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-container input[type="submit"], .form-container input[type="button"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .form-container input[type="submit"]:hover, .form-container input[type="button"]:hover {
            background-color: #45a049;
        }
        .error {
            color: #FF0000;
        }
        .success {
            color: #008000;
        }
        .footer {
            position: fixed;
            left: 0;
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
    <div>
        <div class="form-container">
            <form method="post" action="#">
            <<h1>USER REGISTRATION</h1>

<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <label>Name:</label>
    <input type="text" name="name" value="<?php echo $name; ?>">
    <span class="error">* <?php echo $nameErr;?></span><br>

    <label>Username:</label>
    <input type="text" name="username" value="<?php echo $username; ?>">
    <span class="error">* <?php echo $usernameErr;?></span><br>

    <label>Password:</label>
    <input type="password" name="password">
    <span class="error">* <?php echo $passwordErr;?></span><br>

    <label>Address:</label>
    <input type="text" name="address" value="<?php echo $address; ?>">
    <span class="error">* <?php echo $addressErr;?></span><br>

    <label>Pincode:</label>
    <input type="text" name="pincode" value="<?php echo $pincode; ?>">
    <span class="error">* <?php echo $pincodeErr;?></span><br>

    <label>Email ID:</label>
    <input type="text" name="email" value="<?php echo $email; ?>">
    <span class="error">* <?php echo $emailErr;?></span><br>

    <label>Phone No:</label>
    <input type="text" name="phone" value="<?php echo $phone; ?>"><br>

    <label>Aadhaar Number:</label>
    <input type="text" name="aadharnumber" value="<?php echo $aadharnumber; ?>">
    <span class="error">* <?php echo $aadharErr;?></span><br>

    <label>Gender:</label>
    <input type="radio" name="gender" value="female" <?php if (isset($gender) && $gender=="female") echo "checked"; ?>> Female
    <input type="radio" name="gender" value="male" <?php if (isset($gender) && $gender=="male") echo "checked"; ?>> Male
    <span class="error">* <?php echo $genderErr;?></span><br>

    <input type="submit" name="submit" value="Submit">
    <br><br>
    <input type="button" value="Exit" onclick="location.href='mainpage.html';">
    <br>
    <span class="success"><?php echo $successMsg;?></span>
    <span class="error"><?php echo $errorMsg;?></span>
</form>

    
    <div class="footer">
        <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
    </div>
</body>
</html>
