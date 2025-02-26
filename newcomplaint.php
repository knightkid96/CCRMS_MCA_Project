<?php
// Initialize variables
$responses = [];
$successMsg = '';
$errorMsg = '';
$specErr = '';

// Function to generate a random complaint ID
function generateRandomString($length = 3) {
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = 'C';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Function to sanitize input data
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Decode the JSON data from the request body
    $responses = json_decode(file_get_contents('php://input'), true);

    // Check if $responses is an array and has data
    if (!is_array($responses)) {
        $errorMsg = "Invalid data received.";
    } else {
        // Validate category (specialization)
        if (isset($responses['specialization']) && $responses['specialization'] == '0') {
            $specErr = "Category is required";
        } else {
            $spec = test_input($responses['specialization']);
        }

        // Provide default values for missing fields
        $subject = $responses['subject'] ?? '';
        $details = $responses['details'] ?? '';
        $url = $responses['url'] ?? '';
        $social = $responses['social'] ?? '';
        $suspect = $responses['suspect'] ?? '';
        $dates = $responses['dates'] ?? '';
        $area = $responses['area'] ?? '';

        // Validate required fields
        if (empty($spec) || empty($subject) || empty($details) || empty($url) || empty($dates) || empty($area)) {
            $errorMsg = "Please fill in all required fields.";
        } else {
            // Database connection
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "cybercrimedatabase";

            $conn = new mysqli($servername, $username, $password, $dbname);
            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
            } else {
                // Generate a unique complaint ID
                $c_id = generateRandomString();

                // Insert the complaint into the database
                $sql = "INSERT INTO complaint (c_id, category, subject, details, url, social_media, datetime, suspect, area, status, priority) 
                        VALUES ('$c_id','$spec','$subject','$details','$url','$social','$dates','$suspect','$area','NEW','')";

                if ($conn->query($sql) === TRUE) {
                    // Return the complaint ID as a JSON response
                    echo json_encode(["success" => true, "message" => "Complaint submitted successfully.", "complaint_id" => $c_id]);
                } else {
                    echo json_encode(["success" => false, "message" => "Some Internal Error Occurred. Please Try Again!!!"]);
                }

                $conn->close();
            }
        }
    }
    exit; // Stop further execution after handling the POST request
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

        .header {
            background-color: black;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        .header h1 {
            margin: 0;
            font-size: 2.5rem;
        }

        .chatbox {
            width: 60%;
            margin: 50px auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .messages {
            height: 400px;
            overflow-y: auto;
            border-bottom: 1px solid #ddd;
            padding: 10px;
        }

        .message {
            margin-bottom: 10px;
        }

        .message.bot {
            text-align: left;
        }

        .message.user {
            text-align: right;
        }

        .input-area {
            display: flex;
            margin-top: 10px;
        }

        .input-area input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .input-area button {
            padding: 10px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            margin-left: 10px;
            cursor: pointer;
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
    <div class="header">
        <h1>CYBER CRIME RECORDS MANAGEMENT SYSTEM</h1>
    </div>
    <div class="chatbox">
        <div class="messages" id="messages">
            <div class="message bot">Welcome! Please select a category for your complaint.</div>
        </div>
        <div class="input-area">
            <select id="categoryInput">
                <option value="0">Please Select</option>
                <option value="Bank Account Fraud">Bank Account Fraud</option>
                <option value="Cyberbullying">Cyberbullying</option>
                <option value="Child Pornography">Child Pornography</option>
                <option value="Identity Theft">Identity Theft</option>
                <option value="Social Media Content">Social Media Content</option>
                <option value="Hacking and Viruses">Hacking and Viruses</option>
                <option value="E-Commerce Scam">E-Commerce Scam</option>
                <option value="Email or Phone Call Scam">Email or Phone Call Scam</option>
            </select>
            <button onclick="sendCategory()">Next</button>
        </div>
    </div>
    <div class="footer">
        <p>&copy; Cyber Crime Records Management System By Tejas S, Jain University ,USN:23VMTHR1168</p>
    </div>

    <script>
        let currentStep = 0;
        const steps = [
            { question: "Please enter the subject of your complaint:", field: "subject" },
            { question: "Please provide details of your complaint:", field: "details" },
            { question: "Please enter the URL related to your complaint:(example www.instagram.com)", field: "url" },
            { question: "Please enter any social media details (if applicable):", field: "social" },
            { question: "Please provide suspect details (if any):", field: "suspect" },
            { question: "Please enter the date of the incident(Enter only in yyyy-mm-dd format):", field: "dates" },
            { question: "Please enter the area/place of the incident:", field: "area" }
        ];

        const responses = {};

        function sendCategory() {
            const categoryInput = document.getElementById('categoryInput');
            const selectedCategory = categoryInput.value;

            if (selectedCategory === "0") {
                alert("Category is required.");
                return;
            }

            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML += `<div class="message user">${selectedCategory}</div>`;
            responses['specialization'] = selectedCategory;

            // Move to the next step
            messagesDiv.innerHTML += `<div class="message bot">${steps[currentStep].question}</div>`;
            document.getElementById('categoryInput').style.display = 'none';
            document.querySelector('.input-area button').onclick = sendMessage;
            document.querySelector('.input-area').innerHTML = `
                <input type="text" id="userInput" placeholder="Type your response here...">
                <button onclick="sendMessage()">Next</button>
            `;
        }

        function sendMessage() {
            const userInput = document.getElementById('userInput').value;
            if (userInput.trim() === "") return;

            const messagesDiv = document.getElementById('messages');
            messagesDiv.innerHTML += `<div class="message user">${userInput}</div>`;

            responses[steps[currentStep].field] = userInput;

            if (currentStep < steps.length - 1) {
                currentStep++;
                messagesDiv.innerHTML += `<div class="message bot">${steps[currentStep].question}</div>`;
            } else {
                messagesDiv.innerHTML += `<div class="message bot">Thank you! Your complaint is being submitted...</div>`;
                submitComplaint();
            }

            document.getElementById('userInput').value = '';
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        }

        function submitComplaint() {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "newcomplaint.php", true);
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        document.getElementById('messages').innerHTML += `
                            <div class="message bot">${response.message}</div>
                            <div class="message bot">Your Complaint Number is: <strong>${response.complaint_id}</strong>. Please keep it for future reference.</div>
                        `;
                    } else {
                        document.getElementById('messages').innerHTML += `<div class="message bot">${response.message}</div>`;
                    }
                }
            };

            // Ensure all fields are included in the payload
            const payload = {
                specialization: responses['specialization'] || '',
                subject: responses['subject'] || '',
                details: responses['details'] || '',
                url: responses['url'] || '',
                social: responses['social'] || '',
                suspect: responses['suspect'] || '',
                dates: responses['dates'] || '',
                area: responses['area'] || ''
            };

            xhr.send(JSON.stringify(payload));
        }
    </script>
</body>
</html>