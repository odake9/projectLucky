<?php
// Database connection
$servername = "localhost";
$username = "root"; // default in XAMPP
$password = "";     // default in XAMPP is empty
$dbname = "milk_tea_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];

// Encrypt password before saving
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Insert into DB
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_pass')";

if ($conn->query($sql) === TRUE) {
    echo "Registration successful! <a href='login.html'>Login here</a>";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - Lucky Milk Tea</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
  <div class="container">
    <h1>Create Account</h1>
    <form action="register.php" method="POST">
      <div class="form-control">
        <input type="text" name="name" required>
        <label>Full Name</label>
      </div>

      <div class="form-control">
        <input type="email" name="email" required>
        <label>Email</label>
      </div>

      <div class="form-control">
        <input type="password" name="password" required>
        <label>Password</label>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>
  </div>
</body>
</html>
