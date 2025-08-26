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
                header("Location: admin.php");
            }
            exit();
        } else {
            echo "❌ Wrong password.";
        }
    } else {
        echo "❌ No such user.";
    }
} else {
    echo "⚠️ Please enter both email and password.";
}

$conn->close();
?>
