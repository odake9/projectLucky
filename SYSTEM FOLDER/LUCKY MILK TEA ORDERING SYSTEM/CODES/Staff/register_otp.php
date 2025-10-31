<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$message = "";

/* ------------------------
   STEP 1: REQUEST OTP
------------------------ */
if (isset($_POST['request_otp'])) {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $pass = $_POST['password'];
  $confirm_pass = $_POST['confirm_password'];

  if ($pass !== $confirm_pass) {
    $message = "❌ Passwords do not match!";
  } elseif ($pass !== "admin@2005" && $pass !== "staff@2005") {
    $message = "⚠️ Invalid password! Only authorized staff or admin can register.";
  } else {
    $otp = rand(100000, 999999);
    $_SESSION['otp'] = $otp;
    $_SESSION['email'] = $email;
    $_SESSION['name'] = $name;
    $_SESSION['password'] = $pass;
    $_SESSION['otp_time'] = time();

    // Setup PHPMailer
    $mail = new PHPMailer(true);
    try {
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'luckymilktea88@gmail.com';
      $mail->Password = 'ncim dacq nqsi gaka'; // App Password
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      $mail->setFrom('luckymilktea88@gmail.com', 'Lucky Milk Tea');
      $mail->addAddress($email, $name);

      $mail->isHTML(true);
      $mail->Subject = '🍵 Lucky Milk Tea - OTP Verification';
      $mail->Body = "
        <div style='font-family:Poppins,sans-serif;background:#fff8f0;padding:20px;color:#5c3b28;'>
          <h2>🍵 Lucky Milk Tea Verification</h2>
          <p>Hello <b>$name</b>,</p>
          <p>Your One-Time Password (OTP) is:</p>
          <h1 style='color:#b68c5a;'>$otp</h1>
          <p>This OTP expires in <b>5 minutes</b>. Please do not share it with anyone.</p>
        </div>
      ";

      $mail->send();
      $message = "✅ OTP sent to $email. Please check your inbox.";
    } catch (Exception $e) {
      $message = "❌ OTP could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }
}

/* ------------------------
   STEP 2: CANCEL EMAIL
------------------------ */
if (isset($_POST['cancel_email'])) {
  session_unset();
  $message = "Email cancelled. You can enter a new one.";
}

/* ------------------------
   STEP 3: VERIFY OTP
------------------------ */
if (isset($_POST['verify_otp'])) {
  $entered_otp = $_POST['otp'];

  if (!isset($_SESSION['otp']) || !isset($_SESSION['otp_time'])) {
    $message = "❌ No OTP found. Please request again.";
  } elseif (time() - $_SESSION['otp_time'] > 300) {
    $message = "❌ OTP expired. Please request again.";
    session_unset();
  } elseif ($entered_otp == $_SESSION['otp']) {
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $raw_pass = $_SESSION['password'];
    $hashed_pass = password_hash($raw_pass, PASSWORD_DEFAULT);

    // 🔹 Role assignment restricted to admin/staff only
    if ($raw_pass === "admin@2005") {
      $role = "Admin";
    } elseif ($raw_pass === "staff@2005") {
      $role = "Staff";
    } else {
      $message = "⚠️ Invalid password detected. Registration denied.";
      session_unset();
      $conn->close();
      goto end;
    }

    $sql = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$hashed_pass', '$role')";
    if ($conn->query($sql) === TRUE) {
      echo "
      <!DOCTYPE html>
      <html lang='en'>
      <head>
        <meta charset='UTF-8'>
        <meta http-equiv='refresh' content='3;url=login.html'>
        <title>Registration Successful</title>
        <style>
          body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #ffecd2, #fcb69f);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
          }
          .success-box {
            background: #fff;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            animation: fadeIn 0.8s ease-in-out;
          }
          h2 { color: #4CAF50; margin-bottom: 15px; }
          p { color: #333; }
          @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
          }
        </style>
      </head>
      <body>
        <div class='success-box'>
          <h2>🎉 Register Successfully!</h2>
          <p>Your <b>$role</b> account has been created.</p>
          <p>Redirecting to login page...</p>
        </div>
      </body>
      </html>";
      session_unset();
      exit();
    } else {
      $message = "❌ Database error: " . $conn->error;
    }
  } else {
    $message = "❌ Invalid OTP. Please try again.";
  }
}

end:
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register - Lucky Milk Tea</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #fff8f0;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }
    .register-box {
      background: #ffffff;
      padding: 40px 35px;
      border-radius: 20px;
      width: 100%;
      max-width: 420px;
      text-align: center;
      box-shadow: 0 6px 20px rgba(0,0,0,0.1);
      border: 2px solid #f5e6ca;
    }
    .register-box h2 { color: #8b4513; margin-bottom: 20px; }
    input[type="text"], input[type="email"], input[type="password"] {
      width: 100%; padding: 12px; margin-bottom: 15px;
      border-radius: 10px; border: 1px solid #d9b99b;
      background: #fffdf8; font-size: 15px;
    }
    .password-field { position: relative; }
    .toggle-password {
      position: absolute; top: 50%; right: 12px;
      transform: translateY(-50%);
      cursor: pointer; color: #8b4513;
    }
    button {
      width: 100%; padding: 12px; border: none;
      border-radius: 10px; background-color: #cfa47e;
      color: #fff; font-weight: bold; cursor: pointer;
      transition: 0.3s;
    }
    button:hover { background-color: #b68c68; }
    .cancel-btn { background-color: #f1d3a8; color: #5c3b28; }
    .cancel-btn:hover { background-color: #e4b874; }
    .message { color: #8b4513; margin-bottom: 15px; font-size: 14px; }
  </style>
</head>
<body>

  <div class="register-box w3-animate-top">
    <h2>☕ Create Your Account</h2>
    <?php if ($message) echo "<p class='message'>$message</p>"; ?>

    <!-- Step 1: Email + Password + Request OTP -->
    <form method="POST" class="w3-container">
      <input type="text" name="name" placeholder="👤 Username"
             value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>"
             <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?> required>

      <input type="email" id="email" name="email" placeholder="📧 Email"
             value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>"
             <?php echo isset($_SESSION['email']) ? 'readonly' : ''; ?> required>

      <?php if (!isset($_SESSION['email'])): ?>
        <div class="password-field">
          <input type="password" name="password" id="password" placeholder="🔑 Password" required>
          <i class="fa fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
        </div>
        <div class="password-field">
          <input type="password" name="confirm_password" id="confirm_password" placeholder="🔒 Confirm Password" required>
          <i class="fa fa-eye toggle-password" onclick="togglePassword('confirm_password', this)"></i>
        </div>
        <button type="submit" name="request_otp">Request OTP</button>
      <?php else: ?>
        <button type="submit" name="cancel_email" class="cancel-btn">Cancel</button>
      <?php endif; ?>
    </form>

    <?php if (isset($_SESSION['email'])): ?>
    <hr>
    <!-- Step 2: Verify OTP -->
    <form method="POST" class="w3-container">
      <input type="text" name="otp" placeholder="🔢 Enter OTP" maxlength="6" required>
      <button type="submit" name="verify_otp">Verify OTP</button>
    </form>
    <?php endif; ?>

    <div style="margin-top:20px;">
      <a href="login.html" style="color:#8b4513;text-decoration:none;">← Back to Login</a>
    </div>
  </div>

  <script>
    function togglePassword(id, icon) {
      const input = document.getElementById(id);
      if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
      } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
      }
    }
  </script>

</body>
</html>
