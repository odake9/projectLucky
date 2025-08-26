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

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];

    // Check if image uploaded
    if (!empty($_FILES['profile_image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);

        $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
            // Save with new profile image
            $conn->query("UPDATE users SET name='$name', profile_image='$fileName' WHERE id=$user_id");
        }
    } else {
        // Update without changing image
        $conn->query("UPDATE users SET name='$name' WHERE id=$user_id");
    }

    header("Location: profile.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(120deg, #d5096f, #ab0592);
      color: #fff;
      text-align: center;
      padding: 50px;
    }
    .profile-container {
      background: rgba(0,0,0,0.6);
      padding: 20px 40px;
      border-radius: 12px;
      display: inline-block;
    }
    .profile-card img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #ec9dc5;
    }
    .form-input {
      margin: 15px 0;
      display: block;
    }
    input[type="text"], input[type="file"] {
      padding: 10px;
      width: 90%;
      border-radius: 6px;
      border: none;
      margin-top: 5px;
    }
    .btn {
      padding: 10px 20px;
      border-radius: 8px;
      background: #ec9dc5;
      color: white;
      text-decoration: none;
      display: inline-block;
      border: none;
      cursor: pointer;
    }
    .btn:hover {
      background: #9a0283;
      transform: scale(1.05);
    }
    .logout {
      background: #ff4d4d;
    }
    .logout:hover {
      background: #cc0000;
    }
  </style>
</head>
<body>
  <div class="profile-container">
    <h1>Your Profile</h1>

    <div class="profile-card">
      <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Picture"><br><br>
      <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
      <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
      <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
      <p><strong>Registered:</strong> <?php echo $user['date_registered']; ?></p>
    </div>

    <hr>

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
      <button type="submit" class="btn">Update Profile</button>
    </form>

    <br>
    <a href="admin.php" class="btn">Back to Dashboard</a>
    <br>
    <br>
    <a href="logout.php" class="btn logout">Logout</a>
  </div>
</body>
</html>
