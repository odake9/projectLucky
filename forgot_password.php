<?php
// forgot_password.php

// Connect to DB
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Run only when form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['new_password'])) {
        $email = $_POST['email'];
        $new_password = $_POST['new_password'];

        // Hash password
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Update DB
        $sql = "UPDATE users SET password = ? WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $hashed_password, $email);

        if ($stmt->execute() && $stmt->affected_rows > 0) {
            echo "<p style='color:lime;'>Password reset successfully!</p>";
        } else {
            echo "<p style='color:red;'>Email not found or error updating password.</p>";
        }

        $stmt->close();
    } else {
        echo "<p style='color:red;'>Please fill in all fields.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="forgot.css">
</head>
<body>
  <div class="container">
    <h1>Reset Your Password</h1>
    <form method="POST">
      <div class="form-control">
        <input type="email" name="email" required>
        <label>Email</label>
      </div>
      <div class="form-control">
        <input type="password" name="new_password" required>
        <label>New Password</label>
      </div>
      <button type="submit" class="btn">Reset Password</button>
    </form>
  </div>
</body>
</html>
