<?php
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "DELETE FROM users WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting staff: " . $conn->error;
    }
}
?>
