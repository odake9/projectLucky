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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
    padding: 30px 25px;
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

  form {
    width: 100%;
    margin-top: 10px;
    text-align: left;
  }

  .form-input {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin: 12px 0;
}

.form-input label {
  width: 150px; /* fixed width to align inputs */
  font-weight: 600;
  color: #5a3e2b;
  flex-shrink: 0;
}

input[type="text"],
input[type="file"],
input[type="password"] {
  width: 100%;
  height: 44px;
  padding: 10px 14px;
  border-radius: 10px;
  border: 1px solid #e4cdb5;
  background: #fffaf5;
  outline: none;
  font-size: 14px;
  box-sizing: border-box;
}

  /* === Password Input Section === */
.password-wrapper {
  position: relative;
  flex: 1;
}

.password-wrapper input[type="password"],
.password-wrapper input[type="text"] {
  width: 100%;
  height: 46px;
  padding: 10px 45px 10px 14px; /* padding-right for icon */
  border-radius: 10px;
  border: 1px solid #e4cdb5;
  background: #fffaf5;
  outline: none;
  font-size: 15px;
  box-sizing: border-box;
  transition: border-color 0.3s ease;
}

.password-wrapper input:focus {
  border-color: #d4a373;
  box-shadow: 0 0 5px rgba(212, 163, 115, 0.3);
}

/* 👁 Show/Hide Icon */
.show-password {
  position: absolute;
  right: 14px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 18px;
  color: #9b5c38;
  transition: color 0.3s ease;
}

.show-password:hover {
  color: #b97a56;
}

  .btn {
    width: 100%;
    padding: 12px 0;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 600;
    transition: background 0.3s ease;
    margin-top: 8px;
  }

  .update { background: #d4a373; color: #fff; }
  .update:hover { background: #b97a56; }

  .change { background: #c07f56; color: #fff; }
  .change:hover { background: #9b5c38; }

  .dashboard { background: #5a3e2b; color: #fff; }
  .dashboard:hover { background: #3d2b1f; }

  .logout { background: #e57c73; color: #fff; }
  .logout:hover { background: #cc5a5a; }

  p strong {
    color: #6b4f4f;
  }

 .password-wrapper {
  position: relative;
}

.show-password {
  position: absolute;
  right: 12px;
  top: 38px;
  cursor: pointer;
  font-size: 16px;
  color: #9b5c38;
  transition: color 0.2s ease;
}

.show-password:hover {
  color: #c07f56;
}

.password-box {
  position: relative;
  width: 100%;
}

.password-box input {
  width: 100%;
  padding: 10px 40px 10px 10px; /* space for the icon */
  border-radius: 10px;
  border: 1px solid #e4cdb5;
  background: #fffaf5;
  outline: none;
  font-size: 14px;
  box-sizing: border-box;
}

.toggle-eye {
  position: absolute;
  right: 12px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  font-size: 16px;
  color: #9b5c38;
  transition: color 0.2s ease;
}

.toggle-eye:hover {
  color: #c07f56;
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
    <div class="password-box">
      <input type="password" id="old_password" name="old_password" required>
      <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('old_password', this)"></i>
    </div>
  </div>
  <div class="form-input password-wrapper">
    <label>New Password:</label>
    <div class="password-box">
      <input type="password" id="new_password" name="new_password" required>
      <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('new_password', this)"></i>
    </div>
  </div>
  <div class="form-input password-wrapper">
    <label>Confirm New Password:</label>
    <div class="password-box">
      <input type="password" id="confirm_password" name="confirm_password" required>
      <i class="fa-solid fa-eye toggle-eye" onclick="togglePassword('confirm_password', this)"></i>
    </div>
  </div>
  <button type="submit" name="change_password" class="btn change">🔑 Change Password</button>
</form>

  <br>
</div>

<script>
function togglePassword(id, iconElement) {
  // Reset all other inputs
  document.querySelectorAll('.password-wrapper input').forEach(input => {
    input.type = 'password';
  });
  document.querySelectorAll('.show-password').forEach(icon => {
    icon.classList.remove('fa-eye-slash');
    icon.classList.add('fa-eye');
  });

  const input = document.getElementById(id);
  const isPassword = input.type === "password";

  if (isPassword) {
    input.type = "text";
    iconElement.classList.remove('fa-eye');
    iconElement.classList.add('fa-eye-slash');
  } else {
    input.type = "password";
    iconElement.classList.remove('fa-eye-slash');
    iconElement.classList.add('fa-eye');
  }
}
</script>
<script>
function togglePassword(id, iconElement) {
  const input = document.getElementById(id);
  const isPassword = input.type === "password";

  input.type = isPassword ? "text" : "password";
  iconElement.classList.toggle("fa-eye");
  iconElement.classList.toggle("fa-eye-slash");
}
</script>
</body>
</html>