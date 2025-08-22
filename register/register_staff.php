<?php
$servername = "localhost";
$username = "root"; // change if different
$password = "";     // change if you set one
$dbname = "milk_tea_shop";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $role = $_POST['role'];

    $sql = "INSERT INTO staff (name, role) VALUES ('$name', '$role')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('New staff registered successfully!'); window.location.href='dashboard.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
