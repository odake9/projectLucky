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
            $conn->query("UPDATE users SET name='$name', profile_image='$fileName' WHERE id=$user_id");
        }
    } else {
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
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #ff7eb3, #ff758c, #ff9a8b);
      background-size: 300% 300%;
      animation: gradientMove 10s ease infinite;
      margin: 0;
      padding: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    @keyframes gradientMove {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    .profile-container {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      padding: 40px;
      width: 420px;
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
      transition: transform 0.3s;
    }

    .profile-card img:hover {
      transform: scale(1.08);
    }

    h1 {
      margin-bottom: 10px;
      font-size: 28px;
    }

    h2 {
      margin: 20px 0 10px;
      font-size: 22px;
    }

    p {
      margin: 5px 0;
      font-size: 15px;
    }

    .form-input {
      margin: 15px 0;
      text-align: left;
    }

    label {
      font-weight: bold;
      font-size: 14px;
    }

    input[type="text"], input[type="file"] {
      padding: 12px;
      width: 100%;
      border-radius: 10px;
      border: none;
      margin-top: 8px;
      font-size: 14px;
      outline: none;
      background: rgba(255,255,255,0.2);
      color: #fff;
    }

    input::file-selector-button {
      background: #ff7eb3;
      border: none;
      padding: 8px 15px;
      border-radius: 8px;
      cursor: pointer;
      color: #fff;
      margin-right: 10px;
      transition: background 0.3s;
    }

    input::file-selector-button:hover {
      background: #ff4d94;
    }

    .btn {
      padding: 12px 25px;
      border-radius: 12px;
      border: none;
      color: white;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      margin: 10px 5px;
      display: inline-block;
      text-decoration: none;
    }

    .btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .dashboard {
      background: linear-gradient(45deg, #4facfe, #00f2fe);
    }

    .dashboard:hover {
      background: linear-gradient(45deg, #00c6ff, #0072ff);
    }

    .logout {
      background: linear-gradient(45deg, #ff6a6a, #ff0000);
    }

    .logout:hover {
      background: linear-gradient(45deg, #cc0000, #a80000);
    }
  </style>
</head>
<body>
  <div class="profile-container">
    <div class="profile-card">
      <img src="uploads/<?php echo $user['profile_image']; ?>" alt="Profile Picture"><br><br>
      <h1><?php echo $user['name']; ?></h1>
      <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
      <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
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
      <button type="submit" class="btn dashboard">💾 Update Profile</button>
    </form>

    <br>
    <a href="staff.php" class="btn dashboard">🏠 Dashboard</a>
    <a href="logout.php" class="btn logout">🚪 Logout</a>
  </div>
</body>
</html>
