<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

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
                background: #ffe5e5;
                border: 2px solid #ff6b6b;
                padding: 20px 30px;
                border-radius: 12px;
                text-align: center;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
                max-width: 400px;
            }
            .error-box h2 {
                margin: 0 0 10px;
                font-size: 1.4rem;
                color: #b00020;
            }
            .error-box p {
                color: #333;
                margin: 5px 0 15px;
            }
            .back-btn {
                background: #ff9aa2;
                color: white;
                border: none;
                padding: 10px 20px;
                border-radius: 8px;
                cursor: pointer;
                text-decoration: none;
                transition: background 0.2s;
            }
            .back-btn:hover {
                background: #ff6f91;
            }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <h2>⚠️ Login Failed</h2>
            <p>$message</p>
            <a href='login.html' class='back-btn'>🔙 Back to Login</a>
        </div>
    </body>
    </html>
    ";
    exit();
}

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
                header("Location: staff.php"); // 👈 staff goes to menu
            }
            exit();
        } else {
            showError("❌ The password you entered is incorrect.");
        }
    } else {
        showError("❌ No account found with that email.");
    }
} else {
    showError("⚠️ Please enter both email and password.");
}

$conn->close();
?>
