<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// DB connection
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle Request OTP
if (isset($_POST['request_otp'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if ($check->num_rows > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'luckymilktea88@gmail.com';
            $mail->Password = 'ncim dacq nqsi gaka';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('yourgmail@gmail.com', 'Lucky Milk Tea');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code - Lucky Milk Tea';
            $mail->Body    = "<p>Your OTP code is: <b>$otp</b></p>";

            $mail->send();
            echo "<script>alert('✅ OTP sent successfully to $email');</script>";
        } catch (Exception $e) {
            echo "<script>alert('❌ Failed to send OTP. Error: {$mail->ErrorInfo}');</script>";
        }
    } else {
        echo "<script>alert('❌ Email not found in system!');</script>";
    }
}

// Handle Cancel Button
if (isset($_POST['cancel_email'])) {
    unset($_SESSION['email']);
    unset($_SESSION['otp']);
}

// Handle Reset Password
if (isset($_POST['reset_password'])) {
    $otp = $_POST['otp'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    if (isset($_SESSION['otp']) && $otp == $_SESSION['otp']) {
        $email = $_SESSION['email'];
        $conn->query("UPDATE users SET password='$new_password' WHERE email='$email'");
        echo "<script>alert('✅ Password reset successful! Please login.'); window.location='login.html';</script>";
        session_destroy();
    } else {
        echo "<script>alert('❌ Invalid OTP! Please try again.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password - Lucky Milk Tea</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <style>
    body {
      font-family: "Poppins", sans-serif;
      margin: 0; height: 100vh;
      display: flex; justify-content: center; align-items: center;
      background: linear-gradient(135deg, #ff6ec4, #7873f5);
    }
    .login-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 35px 40px; border-radius: 15px;
      backdrop-filter: blur(10px);
      box-shadow: 0px 8px 25px rgba(0,0,0,0.3);
      width: 100%; max-width: 380px; text-align: center;
      animation: fadeIn 0.8s ease-in-out;
    }
    .login-box h2 { margin-bottom: 20px; color: #fff; font-size: 24px; }
    .login-box input { width: 100%; padding: 12px; margin: 10px 0;
      border: none; border-radius: 8px; font-size: 14px; outline: none; }
    .login-box button { width: 100%; padding: 12px; border: none;
      border-radius: 8px; font-size: 16px; cursor: pointer; margin-top: 15px; }
    .request-btn { background-color: #4facfe; color: #fff; }
    .request-btn:hover { background-color: #00f2fe; }
    .reset-btn { background-color: #ff6ec4; color: #fff; }
    .reset-btn:hover { background-color: #ff9a8b; }
    .cancel-btn { background-color: #f44336; color: #fff; }
    .cancel-btn:hover { background-color: #d32f2f; }
    .login-box a { display: block; margin-top: 12px; font-size: 14px; text-decoration: none; color: #fff; }
    .login-box a:hover { text-decoration: underline; }
    hr { margin: 20px 0; border: none; border-top: 1px solid rgba(255,255,255,0.3); }
    @keyframes fadeIn { from {opacity: 0; transform: translateY(-20px);} to {opacity: 1; transform: translateY(0);} }
  </style>
</head>
<body>
  <div class="login-box w3-card-4 w3-animate-top">
    <h2>🔑 Forgot Password</h2>

    <!-- Step 1: Request OTP -->
    <form method="POST" class="w3-container">
      <input type="email" id="email" name="email" placeholder="📧 Email"
             value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"
             <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?>
             required class="w3-input w3-round">
      <?php if (!isset($_SESSION['email'])): ?>
        <button type="submit" name="request_otp" class="w3-button w3-blue w3-round request-btn">Request OTP</button>
      <?php else: ?>
        <button type="submit" name="cancel_email" class="w3-button w3-red w3-round cancel-btn">Cancel</button>
      <?php endif; ?>
    </form>

    <hr>

    <!-- Step 2: Verify OTP + Reset Password -->
    <form method="POST" class="w3-container">
      <input type="text" name="otp" placeholder="🔢 Enter OTP" required class="w3-input w3-round">
      <input type="password" name="new_password" placeholder="🔒 New Password" required class="w3-input w3-round">
      <button type="submit" name="reset_password" class="w3-button w3-pink w3-round reset-btn">Reset Password</button>
    </form>

    <a href="login.html">⬅ Back to Login</a>
  </div>
</body>
</html>
