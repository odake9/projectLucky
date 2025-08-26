<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to show styled error
function showError($message) {
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>Login Error</title>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #fff8f0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background: #fff;
                padding: 30px;
                border-radius: 15px;
                box-shadow: 0 6px 15px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 100%;
            }
            .error-box h2 {
                color: #ff4d4d;
                margin-bottom: 15px;
                font-size: 22px;
            }
            .error-box p {
                color: #333;
                font-size: 16px;
                margin-bottom: 20px;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background: #ff4d4d;
                color: #fff;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: 0.3s;
            }
            .btn:hover {
                background: #e63939;
            }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h2>⚠️ Login Error</h2>
            <p>$message</p>
            <a href='login.html' class='btn'>🔙 Back to Login</a>
        </div>
    </body>
    </html>
    ";
    exit();
}

// Get form data
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!empty($email) && !empty($pass)) {
    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($pass, $row['password'])) {
            // Save user info in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Redirect based on role
            if ($row['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: staff.php"); 
            }
            exit();
        } else {
            showError("Password is incorrect. Please try again.");
        }
    } else {
        showError("No account found with that email.");
    }
} else {
    showError("Please enter both email and password.");
}

$conn->close();
?>
