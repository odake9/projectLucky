<?php
session_start();

// Check if staff is logged in
if (!isset($_SESSION['staff_id'])) {
    header("Location: login.html");
    exit();
}

// DB connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lucky_milktea";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$staff_id = $_SESSION['staff_id'];

// Get staff details
$sql = "SELECT name, email, role, registered_at FROM staff WHERE id='$staff_id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $staff = $result->fetch_assoc();
} else {
    echo "Staff not found.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Profile - Lucky Milk Tea</title>
  <link rel="stylesheet" href="profile.css">
</head>
<body>
  <div class="container">
    <h1>Staff Profile</h1>
    <div class="profile-card">
      <p><strong>Name:</strong> <?php echo $staff['name']; ?></p>
      <p><strong>Email:</strong> <?php echo $staff['email']; ?></p>
      <p><strong>Role:</strong> <?php echo $staff['role']; ?></p>
      <p><strong>Registered At:</strong> <?php echo $staff['registered_at']; ?></p>
    </div>
    <br>
    <a href="dashboard.php" class="btn">Back to Dashboard</a>
    <a href="logout.php" class="btn btn-danger">Logout</a>
  </div>
</body>
</html>
