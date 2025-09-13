<?php
session_start();

// Connect to DB
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

// Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Function to show styled messages
function showMessage($message, $type = "error", $link = "forgot_password.php") {
    $color = $type === "success" ? "#4CAF50" : "#F44336";
    echo "
    <div style='
        max-width:500px;
        margin:50px auto;
        padding:25px;
        border-radius:10px;
        background:#fff;
        box-shadow:0 4px 8px rgba(0,0,0,0.1);
        text-align:center;
        font-family: Arial, sans-serif;
    '>
        <h2 style='color:$color;'>$message</h2>
        <a href='$link' style='
            display:inline-block;
            margin-top:20px;
            padding:10px 25px;
            background:#007BFF;
            color:#fff;
            text-decoration:none;
            border-radius:5px;
            font-weight:bold;
            transition:0.3s;
        ' onmouseover=\"this.style.background='#0056b3'\" onmouseout=\"this.style.background='#007BFF'\">🏠 Back</a>
    </div>";
    exit();
}

// Step 1: Request OTP
if (isset($_POST['request_otp'])) {
    $email = $_POST['email'];
    $check = $conn->prepare("SELECT * FROM users WHERE email=?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['reset_email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'luckymilktea88@gmail.com';
            $mail->Password = 'ncim dacq nqsi gaka';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('luckymilktea88@gmail.com', 'Lucky Milk Tea');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Your Password Reset OTP - Lucky Milk Tea";
            $mail->Body    = "<p>Your OTP is: <b>$otp</b></p>";
            $mail->send();

            showMessage("✅ OTP sent to your email!", "success", "forgot_password.html");
        } catch (Exception $e) {
            showMessage("❌ Mailer Error: {$mail->ErrorInfo}. For testing, OTP = $otp", "error");
        }
    } else {
        showMessage("❌ Email not found!", "error", "forgot_password.html");
    }
}

// Step 2: Verify OTP and Reset Password
elseif (isset($_POST['reset_password'])) {
    if (!isset($_SESSION['reset_email']) || !isset($_SESSION['otp'])) {
        showMessage("⚠️ No OTP request found. Please request OTP first.", "error");
    }

    $email = $_SESSION['reset_email'];
    $otp_entered = $_POST['otp'];
    $new_password = $_POST['new_password'];

    if ($otp_entered == $_SESSION['otp']) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE email=?");
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute()) {
            unset($_SESSION['otp']);
            unset($_SESSION['reset_email']);
            showMessage("✅ Password reset successfully!", "success", "login.html");
        } else {
            showMessage("❌ Error updating password. Please try again.", "error");
        }
        $stmt->close();
    } else {
        showMessage("❌ Invalid OTP!", "error", "forgot_password.html");
    }
} else {
    showMessage("⚠️ Invalid Request!", "error", "forgot_password.html");
}

$conn->close();
?>
