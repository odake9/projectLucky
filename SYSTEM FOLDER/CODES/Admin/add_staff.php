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
  <title>Add Staff - Lucky Milk Tea</title>
  <style>
    /* ===== BASE STYLES ===== */
    body {
      font-family: "Poppins", sans-serif;
      background-color: #fff8f0;
      color: #4b3b2f;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 550px;
      margin: 80px auto;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(107, 79, 79, 0.15);
      padding: 40px 50px;
      text-align: center;
    }

    h2 {
      margin-bottom: 20px;
      color: #6b4f4f;
      font-size: 1.8rem;
    }

    /* ===== INPUT STYLING ===== */
    input[type="text"],
    input[type="email"],
    input[type="password"],
    select {
      width: 100%;
      padding: 12px 15px;
      margin: 10px 0 18px;
      border: 1px solid #d5b59c;
      border-radius: 10px;
      font-size: 1rem;
      background-color: #fffdf9;
      color: #4b3b2f;
      box-sizing: border-box;
      transition: 0.3s ease;
    }

    input:focus,
    select:focus {
      outline: none;
      border-color: #f7b267;
      box-shadow: 0 0 5px rgba(247, 178, 103, 0.5);
    }

    /* ===== BUTTONS ===== */
    .btn {
      display: inline-block;
      padding: 12px 24px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
      font-size: 1rem;
    }

    .btn-submit {
      background: #f7b267;
      color: #fff;
      margin-right: 10px;
    }
    .btn-submit:hover {
      background: #f4845f;
    }

    .btn-back {
      background: #b68c5a;
      color: #fff;
      text-decoration: none;
    }
    .btn-back:hover {
      background: #8c6b4a;
    }

    /* ===== DECORATIVE ELEMENT ===== */
    .logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .logo img {
      height: 70px;
      width: auto;
      object-fit: contain;
    }

    .logo span {
      font-size: 1.5rem;
      font-weight: 600;
      color: #b68c5a;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="logo">
      <img src="logo.png" alt="Lucky Logo">
      <span>Lucky Milk Tea</span>
    </div>

    <h2>➕ Add New Staff</h2>

    <form method="post">
      <input type="text" name="name" placeholder="Full Name" required>
      <input type="email" name="email" placeholder="Email" required>

      <select name="role" required>
        <option value="" disabled selected>Select Role</option>
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
      </select>

      <input type="password" name="password" placeholder="Password" required>

      <div>
        <button type="submit" class="btn btn-submit">✅ Add Staff</button>
        <a href="staff_manage.php" class="btn btn-back">⬅ Cancel</a>
      </div>
    </form>
  </div>

</body>
</html>
<?php $conn->close(); ?>
