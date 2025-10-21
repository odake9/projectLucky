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
        <title>Login Error - Lucky Milk Tea</title>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap' rel='stylesheet'>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #fdf8f3, #f1e1c6);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }

            .error-box {
                background: #fff;
                padding: 35px;
                border-radius: 15px;
                box-shadow: 0 6px 20px rgba(0,0,0,0.1);
                text-align: center;
                max-width: 400px;
                width: 90%;
                animation: fadeIn 0.6s ease-in-out;
            }

            .error-icon {
                font-size: 2.5rem;
                color: #c0392b;
                margin-bottom: 10px;
            }

            h2 {
                color: #b68c5a;
                margin-bottom: 12px;
                font-size: 1.4rem;
                font-weight: 600;
            }

            p {
                color: #3c2f2f;
                font-size: 0.95rem;
                margin-bottom: 20px;
            }

            .btn {
                display: inline-block;
                padding: 10px 25px;
                background: #b68c5a;
                color: #fff;
                border-radius: 25px;
                text-decoration: none;
                font-size: 0.9rem;
                font-weight: 500;
                transition: background 0.3s ease, transform 0.2s ease;
            }

            .btn:hover {
                background: #a47b48;
                transform: translateY(-2px);
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-15px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class='error-box'>
            <div class='error-icon'>⚠️</div>
            <h2>Login Error</h2>
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
    showError("Please fill in both email and password fields.");
}

$conn->close();
?>
