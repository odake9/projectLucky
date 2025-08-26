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

// ===== Handle Form Submit =====
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // encrypt password
    $role     = $_POST['role'];

    $sql = "INSERT INTO users (name, email, password, role, date_registered) 
            VALUES ('$name', '$email', '$password', '$role', NOW())";

    if ($conn->query($sql) === TRUE) {
        echo "New staff added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Staff</title>
  <style>
    body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
    form { background: #fff; padding: 20px; border-radius: 8px; max-width: 400px; margin: auto; }
    label { display: block; margin: 10px 0 5px; }
    input, select { width: 100%; padding: 8px; margin-bottom: 15px; }
    button { background: #28a745; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
    button:hover { background: #218838; }
  </style>
</head>
<body>

<h2>Add New Staff</h2>
<form method="POST">
  <label>Name</label>
  <input type="text" name="name" required>

  <label>Email</label>
  <input type="email" name="email" required>

  <label>Password</label>
  <input type="password" name="password" required>

  <label>Role</label>
  <select name="role" required>
    <option value="staff">Staff</option>
    <option value="admin">Admin</option>
  </select>

  <button type="submit">Add Staff</button>
</form>

</body>
</html>
