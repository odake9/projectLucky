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
        echo "<script>alert('New staff added successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $conn->error . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Staff</title>
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
      margin: 0;
      padding: 40px;
    }
    .container {
      max-width: 450px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.15);
      animation: fadeIn 1s ease-in-out;
    }
    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 25px;
    }
    label {
      font-weight: 500;
      margin-bottom: 6px;
      display: block;
      color: #444;
    }
    input, select {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 18px;
      font-size: 14px;
    }
    input:focus, select:focus {
      border-color: #f6ad55;
      outline: none;
      box-shadow: 0 0 5px rgba(246, 173, 85, 0.6);
    }
    .btn {
      width: 100%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    .btn-add {
      background: #28a745;
      color: white;
    }
    .btn-add:hover {
      background: #218838;
    }
    .btn-home {
      display: block;
      text-align: center;
      margin-top: 12px;
      background: #f76c6c;
      color: white;
      text-decoration: none;
      padding: 10px;
      border-radius: 8px;
      font-size: 15px;
      transition: 0.3s;
    }
    .btn-home:hover {
      background: #e63946;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>
<body>

<div class="container">
  <h2>➕ Add New Staff</h2>
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

    <button type="submit" class="btn btn-add">Add Staff</button>
  </form>

  <a href="admin.php" class="btn-home">🏠 Back to Dashboard</a>
</div>

</body>
</html>
