<?php
session_start();
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// simulate login (replace with your real login system)
if (!isset($_SESSION['user_id'])) {
    $_SESSION['user_id'] = 1; // Example: logged-in user is ID=1
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$result = $conn->query("SELECT * FROM users WHERE id=$user_id");
$user = $result->fetch_assoc();

// Handle profile update (name & image)
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

    $stored_pass = $user['password']; // from DB

    $is_valid = password_verify($old_password, $stored_pass) || $old_password === $stored_pass;

    if ($is_valid) {
        if ($new_password === $confirm_password) {
            $hashed_new = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hashed_new, $user_id);
            $stmt->execute();
            $stmt->close();
            $msg = "<p class='w3-text-green'>✅ Password changed successfully!</p>";
        } else {
            $msg = "<p class='w3-text-red'>⚠️ New passwords does not match!</p>";
        }
    } else {
        $msg = "<p class='w3-text-red'>⚠️ Old password is incorrect!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile</title>

  <!-- W3.CSS Framework -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ff7eb3, #ff758c, #ff9a8b);
      background-size: 300% 300%;
      animation: gradientMove 10s ease infinite;
      margin: 0;
      padding: 0;
    }
    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }
    .profile-container {
      max-width: 450px;
      margin: 50px auto;
      padding: 30px;
      background: rgba(255,255,255,0.1);
      border-radius: 20px;
      text-align: center;
      backdrop-filter: blur(12px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    .profile-card img {
      width: 130px;
      height: 130px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #fff;
      box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    h1, h2 { margin: 10px 0; }
    .form-input { text-align: left; margin: 10px 0; }
    label { font-weight: bold; }
    input[type="text"], input[type="file"], input[type="password"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 10px;
      border: none;
      outline: none;
      background: rgba(255,255,255,0.2);
      color: #fff;
    }
    .btn { padding: 10px 20px; border-radius: 12px; border: none; cursor: pointer; margin: 5px; text-decoration: none; }
    .dashboard { background-color: #4facfe; color: #fff; }
    .logout { background-color: #ff6a6a; color: #fff; }
  </style>
</head>
<body>

<div class="profile-container w3-card-4 w3-padding-16">
  <div class="profile-card">
    <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Picture"><br><br>
    <h1 class="w3-text-white"><?php echo $user['name']; ?></h1>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
    <p><strong>Registered:</strong> <?php echo $user['date_registered']; ?></p>
  </div>

  <h2 class="w3-text-white">Edit Profile</h2>
  <form method="POST" enctype="multipart/form-data" class="w3-container">
    <div class="form-input">
      <label>Name:</label>
      <input type="text" name="name" value="<?php echo $user['name']; ?>" required>
    </div>
    <div class="form-input">
      <label>Upload New Picture:</label>
      <input type="file" name="profile_image" accept="image/*">
    </div>
    <button type="submit" name="update_profile" class="btn w3-button w3-blue">💾 Update Profile</button>
  </form>

  <h2 class="w3-text-white">Change Password</h2>
  <?php if (!empty($msg)) echo $msg; ?>
  <form method="POST" class="w3-container">
    <div class="form-input">
      <label>Old Password:</label>
      <input type="password" name="old_password" required>
    </div>
    <div class="form-input">
      <label>New Password:</label>
      <input type="password" name="new_password" required>
    </div>
    <div class="form-input">
      <label>Confirm New Password:</label>
      <input type="password" name="confirm_password" required>
    </div>
    <button type="submit" name="change_password" class="btn w3-button w3-green">🔑 Change Password</button>
  </form>

  <br>
  <a href="staff.php" class="btn w3-button w3-orange">🏠 Dashboard</a>
  <a href="logout.php" class="btn w3-button w3-red">🚪 Logout</a>
</div>

</body>
</html>
