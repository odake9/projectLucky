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

            $mail->setFrom('luckymilktea88@gmail.com', 'Lucky Milk Tea');
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
      background-color: #fff8f0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 0;
    }

    .login-box {
      background: #ffffff;
      padding: 40px 35px;
      border-radius: 20px;
      width: 100%;
      max-width: 420px;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      border: 2px solid #f5e6ca;
    }

    .login-box h2 {
      margin-bottom: 25px;
      color: #8b4513;
      font-weight: 600;
    }

    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 8px 0 15px 0;
      border-radius: 10px;
      border: 1px solid #d9b99b;
      outline: none;
      font-size: 15px;
      background-color: #fffdf8;
      transition: border-color 0.3s;
    }

    .login-box input:focus {
      border-color: #cfa47e;
      box-shadow: 0 0 5px rgba(207,164,126,0.4);
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      margin-top: 10px;
      border: none;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
    }

    .request-btn {
      background-color: #cfa47e;
    }
    .request-btn:hover {
      background-color: #b68c68;
    }

    .reset-btn {
      background-color: #d4a373;
    }
    .reset-btn:hover {
      background-color: #b98b59;
    }

    .cancel-btn {
      background-color: #e57373;
    }
    .cancel-btn:hover {
      background-color: #d32f2f;
    }

    hr {
      margin: 25px 0;
      border: none;
      border-top: 1px solid #f2dfc2;
    }

    .login-box a {
      display: inline-block;
      margin-top: 10px;
      color: #8b4513;
      text-decoration: none;
      background-color: #f9e4c8;
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .login-box a:hover {
      background-color: #f1d3a8;
    }

    .logo {
      width: 70px;
      height: 70px;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="login-box w3-animate-top">
    <h2>🔑 Forgot Password</h2>

    <!-- Step 1: Request OTP -->
    <form method="POST" class="w3-container">
      <input type="email" id="email" name="email" placeholder="📧 Email"
             value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"
             <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?>
             required>
      <?php if (!isset($_SESSION['email'])): ?>
        <button type="submit" name="request_otp" class="request-btn">Request OTP</button>
      <?php else: ?>
        <button type="submit" name="cancel_email" class="cancel-btn">Cancel</button>
      <?php endif; ?>
    </form>

    <hr>

    <!-- Step 2: Verify OTP + Reset Password -->
    <form method="POST" class="w3-container">
      <input type="text" name="otp" placeholder="🔢 Enter OTP" required>
      <input type="password" name="new_password" placeholder="🔒 New Password" required>
      <button type="submit" name="reset_password" class="reset-btn">Reset Password</button>
    </form>

    <a href="login.html">⬅ Back to Login</a>
  </div>
</body>
</html>
