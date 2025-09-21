<?php
// ===== DB Connection =====
$host = "localhost";
$user = "root";
$pass = "";
$db   = "milk_tea_shop";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM users WHERE id=$id");
if ($result->num_rows == 0) {
    die("⚠️ Staff not found!");
}
$staff = $result->fetch_assoc();

// ===== Handle Update =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role  = $conn->real_escape_string($_POST['role']);

    $sql = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id";
    if ($conn->query($sql)) {
        echo "<script>
            alert('✅ Staff details updated successfully!');
            window.location='staff_manage.php';
        </script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Staff - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <style>
    body { font-family:'Poppins', sans-serif; background:#fdf6ec; padding:20px; }
    .form-box { max-width:500px; margin:auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
    h2 { text-align:center; color:#6d4c41; }
    .w3-button { border-radius:8px; }
  </style>
</head>
<body>

<div class="form-box">
  <h2>✏️ Edit Staff</h2>
  <form method="POST">
    <p><label>Name</label>
      <input class="w3-input w3-border" type="text" name="name" value="<?= htmlspecialchars($staff['name']) ?>" required>
    </p>
    <p><label>Email</label>
      <input class="w3-input w3-border" type="email" name="email" value="<?= htmlspecialchars($staff['email']) ?>" required>
    </p>
    <p><label>Role</label>
      <select class="w3-input w3-border" name="role" required>
        <option value="admin" <?= $staff['role']=='admin'?'selected':'' ?>>Admin</option>
        <option value="staff" <?= $staff['role']=='staff'?'selected':'' ?>>Staff</option>
      </select>
    </p>
    <button type="submit" class="w3-button w3-green w3-block">💾 Save Changes</button>
    <a href="staff_manage.php" class="w3-button w3-red w3-block">❌ Cancel</a>
  </form>
</div>

</body>
</html>
<?php $conn->close(); ?>
