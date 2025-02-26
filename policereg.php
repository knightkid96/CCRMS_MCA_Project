<?php
// define variables and set to empty values
$nameErr = $usernameErr = $passwordErr = $addressErr = $phoneErr = $genderErr = $specErr = "";
$name = $username = $password = $address = $phone = $gender = $spec = "";
$successMsg = $errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["name"])) {
    $nameErr = "Name is required";
  } else {
    $name = test_input($_POST["name"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
      $nameErr = "Only letters and white space allowed"; 
    }
  }
  
  if (empty($_POST["username"])) {
    $usernameErr = "Username is required";
  } else {
    $username = test_input($_POST["username"]);
    // check if username only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z0-9 ]*$/",$username)) {
      $usernameErr = "Only letters, numbers and white space allowed"; 
    }
  }
  
  if (empty($_POST["password"])) {
    $passwordErr = "Password is required";
  } else {
    $password = test_input($_POST["password"]);
    // check if password only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z0-9 ]*$/",$password)) {
      $passwordErr = "Only letters, numbers and white space allowed"; 
    }
  }
  
  if (empty($_POST["address"])) {
    $addressErr = "Address is required";
  } else {
    $address = test_input($_POST["address"]);
  }
  
  if (empty($_POST["gender"])) {
    $genderErr = "Gender is required";
  } else {
    $gender = test_input($_POST["gender"]);
  }
  
  if (empty($_POST["phone"])) {
    $phoneErr = "Phone number is required";
  } else {
    $phone = test_input($_POST["phone"]);
    if (!preg_match("/^[0-9]*$/",$phone)) {
      $phoneErr = "Only numbers allowed"; 
    }
  }
  
  if (isset($_POST['specialization']) && $_POST['specialization'] == '0') { 
    $specErr = "Specialization is required";
} else if (isset($_POST['specialization'])) {
    $spec = test_input($_POST["specialization"]);
}


}

if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["name"]) && !empty($_POST["address"]) && !empty($_POST["phone"]) && !empty($_POST["gender"]) && isset($_POST['specialization']) && $_POST['specialization'] != '0' && $phoneErr == "" && $passwordErr == "" && $usernameErr == "" && $nameErr == "")
{
  $servername= "localhost";
  $usernamed = "root";
  $passwords = "";
  $dbname = "cybercrimedatabase";
  
  $conn = new mysqli($servername, $usernamed, $passwords, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  $sql = "INSERT INTO police (police_id, name, password, phone, gender, address, specialization) VALUES ('$username','$name','$password','$phone','$gender','$address','$spec')";
  if ($conn->query($sql) === TRUE) {
    $successMsg = "Registration Successful. Click on BACK button at the top to LOGIN.";
    echo "<script>
                    alert('Registration Successful, redirecting you to the login page.');
                            window.location.href = 'policeloginregister.php';
                  </script>";
  } else {
    $errorMsg = "Username/Police ID $username already exists. Please enter another username/Police ID.";
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
        <script>
        function validatePoliceID() {
            let validIDs = [
                "7636581368", "8433552890", "4644812021", "4671494392", "1384713483",
                "7350939933", "7054598506", "5674792758", "7902397857", "5375017112",
                "9829643913", "2390883663", "5441264304", "0805622246", "1930661635",
                "8372816138", "4809883118", "2816556690", "6923797488", "3694136434","1234123445","7878454512"
            ];
            
            let inputID = document.getElementById("police_id").value;
            let submitButton = document.getElementById("submitBtn");

            if (validIDs.includes(inputID)) {
                submitButton.disabled = false;
                document.getElementById("errorMsg").innerHTML = "";
            } else {
                submitButton.disabled = true;
                document.getElementById("errorMsg").innerHTML = "Invalid Police ID!";
            }
        }
    </script>
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
                background-color: rgba(255, 255, 255, 0.7);
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.5);
            }
            .form-container input[type="text"], .form-container input[type="password"], .form-container input[type="email"], .form-container select {
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
        <div class="form-container">
            <form method="post" action="#">
                <h1>Police Registration</h1>
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $name; ?>"><span class="error">* <?php echo $nameErr;?></span><br>

                <label>Username/Police ID:</label>
                <input type="text" name="username" value="<?php echo $username; ?>"><span class="error">* <?php echo $usernameErr;?></span><br>

                <label>Password:</label>
                <input type="password" name="password"><span class="error">* <?php echo $passwordErr;?></span><br>

                <label>Address:</label>
                <input type="text" name="address" value="<?php echo $address; ?>"><span class="error">* <?php echo $addressErr;?></span><br>

                <label>Phone No:</label>
                <input type="text" name="phone" value="<?php echo $phone; ?>"><span class="error">* <?php echo $phoneErr;?></span><br>

                <label>Gender:</label>
                <input type="radio" name="gender" value="female" <?php if (isset($gender) && $gender=="female") echo "checked"; ?>> Female
                <input type="radio" name="gender" value="male" <?php if (isset($gender) && $gender=="male") echo "checked"; ?>> Male
                <span class="error">* <?php echo $genderErr;?></span><br>

                <label>Specialization:</label>
<select name="specialization" required>
    <option value="0">Please Select</option>
    <option value="Bank Account Fraud">Bank Account Fraud</option>
    <option value="Cyberbullying">Cyberbullying</option>
    <option value="Child Pornography">Child Pornography</option>
    <option value="Identity Theft">Identity Theft</option>
    <option value="Social Media Crime">Social Media Crime</option>
    <option value="Hacking and Viruses">Hacking and Viruses</option>
    <option value="E-Commerce Scam">E-Commerce Scam</option>
    <option value="Email or Phone Call Scam">Email or Phone Call Scam</option>
</select>
<span class="error">* <?php echo $specErr; ?></span><br>

                <form method="post" action="#">

        <label>Police ID:</label>
        <input type="text" id="police_id" name="police_id" maxlength="10" onkeyup="validatePoliceID()" required>
        <span id="errorMsg" style="color: red;"></span>
        <br>

        <input type="submit" id="submitBtn" value="Submit" disabled>
        <br><br>

        <input type="button" value="Exit" onclick="location.href='mainpage.html';">
        <br><span class="success"><?php echo $successMsg;?></span>
        <span class="error"><?php echo $errorMsg;?></span>
    </form>

        <div class="footer">
        <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
        </div>
    </body>
</html>