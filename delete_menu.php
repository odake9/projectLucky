<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','staff'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Validate menu ID
if (!isset($_GET['id'])) {
    die("⚠️ Menu ID missing!");
}
$id = intval($_GET['id']);

// Delete image file if exists
$result = $conn->query("SELECT image FROM menu WHERE id=$id");
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($row['image'] && file_exists("uploads/menu/" . $row['image'])) {
        unlink("uploads/menu/" . $row['image']); // remove file
    }
}

// Delete from database
$conn->query("DELETE FROM menu WHERE id=$id");

echo "<script>alert('🗑️ Menu deleted successfully!'); window.location.href='admin_menu.php';</script>";
?>
