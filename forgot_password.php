<?php
session_start();

// Connect to DB
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Step 1: Request OTP
if (isset($_POST['request_otp'])) {
    $email = $_POST['email'];

    // Check if email exists
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        // Generate random OTP
        $otp = rand(100000, 999999);

        // Store OTP in session (or DB if you want persistent OTPs)
        $_SESSION['otp'] = $otp;
        $_SESSION['reset_email'] = $email;

        // Send OTP via email (simple PHP mail)
        $subject = "Your Password Reset OTP - Lucky Milk Tea";
        $message = "Your OTP is: $otp";
        $headers = "From: no-reply@luckymilktea.com";

        // ⚠️ For local XAMPP, mail() might not work. Use PHPMailer instead.
        if (mail($email, $subject, $message, $headers)) {
            echo "<p style='color:lime;'>OTP sent to your email!</p>";
        } else {
            echo "<p style='color:red;'>Failed to send OTP (mail not configured). Show OTP here for testing: $otp</p>";
        }
    } else {
        echo "<p style='color:red;'>Email not found!</p>";
    }
}

// Step 2: Verify OTP and Reset Password
if (isset($_POST['reset_password'])) {
    $email = $_SESSION['reset_email'];
    $otp_entered = $_POST['otp'];
    $new_password = $_POST['new_password'];

    if ($otp_entered == $_SESSION['otp']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            echo "<p style='color:lime;'>Password reset successfully!</p>";
            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
        } else {
            echo "<p style='color:red;'>Error updating password.</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red;'>Invalid OTP!</p>";
    }
}

$conn->close();
?>
