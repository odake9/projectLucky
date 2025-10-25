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

// ===== Add Staff =====
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role  = $conn->real_escape_string($_POST['role']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (name, email, role, password, date_registered) 
            VALUES ('$name', '$email', '$role', '$pass', NOW())";

    if ($conn->query($sql) === TRUE) {
        // ✅ Redirect automatically to staff_manage.php after successful insert
        echo "<script>
                alert('✅ New staff added successfully!');
                window.location.href = 'staff_manage.php';
              </script>";
        exit;
    } else {
        echo "<script>
                alert('❌ Error: " . addslashes($conn->error) . "');
                window.history.back();
              </script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Staff</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #fdf6ec; }
    .form-card { max-width: 500px; margin: 50px auto; }
    .btn-submit { background: #28a745; color: white; }
    .btn-submit:hover { background: #218838; }
    .btn-back { background: #f76c6c; color: white; }
    .btn-back:hover { background: #e63946; }
  </style>
</head>
<body>

<div class="w3-card-4 w3-white w3-padding form-card">
  <h2 class="w3-center">➕ Add New Staff</h2>


  <form method="post">
    <p><input class="w3-input w3-border" type="text" name="name" placeholder="Full Name" required></p>
    <p><input class="w3-input w3-border" type="email" name="email" placeholder="Email" required></p>
    <p>
      <select class="w3-select w3-border" name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
      </select>
    </p>
    <p><input class="w3-input w3-border" type="password" name="password" placeholder="Password" required></p>

    <div class="w3-center">
      <button type="submit" class="w3-button btn-submit">✅ Add Staff</button>
      <a href="staff_manage.php" class="w3-button btn-back">⬅ Cancel</a>
    </div>
  </form>
</div>

</body>
</html>
<?php $conn->close(); ?>
