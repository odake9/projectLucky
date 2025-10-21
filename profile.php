<?php
session_start();
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Simulate login (replace with real login system)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = $_POST['name'];

    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);

        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            $conn->query("UPDATE users SET name='$name', profile_image='$fileName' WHERE id=$user_id");
        }
    } else {
        $conn->query("UPDATE users SET name='$name' WHERE id=$user_id");
    }

    echo "<script>
        alert('✅ Profile updated successfully!');
        window.location.href='profile.php';
    </script>";
    exit();
}

// Handle password change
if (isset($_POST['change_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stored_pass = $user['password'];

    $is_valid = password_verify($old_password, $stored_pass) || $old_password === $stored_pass;

    if ($is_valid) {
        if ($new_password === $confirm_password) {
            $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed_new, $user_id);
            $stmt->execute();
            $stmt->close();
            $msg = "<p style='color:green;'>✅ Password changed successfully!</p>";
        } else {
            $msg = "<p style='color:red;'>⚠️ New passwords do not match!</p>";
        }
    } else {
        $msg = "<p style='color:red;'>⚠️ Old password is incorrect!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(to bottom right, #fffaf5, #f8efe6);
      margin: 0;
      padding: 40px;
      color: #4b3b2f;
    }

    .profile-container {
      max-width: 500px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(90, 62, 43, 0.15);
      padding: 30px;
      text-align: center;
    }

    .profile-card img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #d4a373;
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
      margin-bottom: 10px;
    }

    h1 {
      font-size: 1.8rem;
      color: #5a3e2b;
      margin-bottom: 5px;
    }

    h2 {
      margin-top: 25px;
      color: #9b5c38;
      font-size: 1.2rem;
      border-bottom: 2px solid #f2e2d3;
      display: inline-block;
      padding-bottom: 5px;
    }

    .form-input {
      text-align: left;
      margin: 12px 0;
    }

    label {
      font-weight: 600;
      color: #5a3e2b;
    }

    input[type="text"], input[type="file"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 10px;
      border: 1px solid #e4cdb5;
      background: #fffaf5;
      outline: none;
      font-size: 14px;
    }

    .btn {
      padding: 10px 20px;
      border-radius: 10px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      transition: background 0.3s ease;
      margin: 6px;
    }

    .update { background: #d4a373; color: #fff; }
    .update:hover { background: #b97a56; }

    .change { background: #c07f56; color: #fff; }
    .change:hover { background: #9b5c38; }

    .dashboard { background: #5a3e2b; color: #fff; }
    .dashboard:hover { background: #3d2b1f; }

    .logout { background: #e57c73; color: #fff; }
    .logout:hover { background: #cc5a5a; }

    .show-password {
      position: absolute;
      right: 12px;
      top: 36px;
      cursor: pointer;
      font-size: 0.9rem;
      color: #9b5c38;
    }

    .password-wrapper {
      position: relative;
    }

    p strong {
      color: #6b4f4f;
    }
  </style>
</head>
<body>

<div class="profile-container">
  <div class="profile-card">
    <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Picture"><br>
    <h1><?php echo $user['name']; ?></h1>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Role:</strong> <?php echo ucfirst($user['role']); ?></p>
    <p><strong>Registered:</strong> <?php echo $user['date_registered']; ?></p>
  </div>

  <h2>Edit Profile</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-input">
      <label>Name:</label>
      <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
    </div>
    <div class="form-input">
      <label>Upload New Picture:</label>
      <input type="file" name="profile_image" accept="image/*">
    </div>
    <button type="submit" name="update_profile" class="btn update">💾 Update Profile</button>
  </form>

  <h2>Change Password</h2>
  <?php if (!empty($msg)) echo $msg; ?>
  <form method="POST">
    <div class="form-input password-wrapper">
      <label>Old Password:</label>
      <input type="password" id="old_password" name="old_password" required>
      <span class="show-password" onclick="togglePassword('old_password')">👁</span>
    </div>
    <div class="form-input password-wrapper">
      <label>New Password:</label>
      <input type="password" id="new_password" name="new_password" required>
      <span class="show-password" onclick="togglePassword('new_password')">👁</span>
    </div>
    <div class="form-input password-wrapper">
      <label>Confirm New Password:</label>
      <input type="password" id="confirm_password" name="confirm_password" required>
      <span class="show-password" onclick="togglePassword('confirm_password')">👁</span>
    </div>
    <button type="submit" name="change_password" class="btn change">🔑 Change Password</button>
  </form>

  <br>
  <a href="admin.php" class="btn dashboard">🏠 Dashboard</a>
  <a href="logout.php" class="btn logout">🚪 Logout</a>
</div>

<script>
function togglePassword(id) {
  const input = document.getElementById(id);
  input.type = input.type === "password" ? "text" : "password";
}
</script>

</body>
</html>
