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

// ===== Get staff by ID =====
if (!isset($_GET['id'])) {
    die("No staff ID provided.");
}
$id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM users WHERE id=$id");

if ($result->num_rows == 0) {
    die("Staff not found.");
}
$staff = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Profile</title>
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #fdf6ec; }
    .profile-card { max-width: 700px; margin: 50px auto; }
    .profile-table { width: 100%; border-collapse: collapse; }
    .profile-table td, .profile-table th {
      border: 1px solid #ddd;
      padding: 12px;
    }
    .profile-table th {
      background: #f76c6c;
      color: white;
      text-align: left;
    }
    .profile-img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid #f76c6c;
    }
    .btn-back { background: #f76c6c; color: white; margin-top: 15px; }
    .btn-back:hover { background: #e63946; }
  </style>
</head>
<body>

<div class="w3-card-4 w3-white w3-padding profile-card">
  <h2 class="w3-center">👤 Staff Profile</h2>

  <table class="profile-table">
    <tr>
      <th>Profile Picture</th>
      <td><img src="uploads/<?= htmlspecialchars($staff['profile_image'] ?? 'default.png') ?>" 
               alt="Profile Picture" class="profile-img"></td>
    </tr>
    <tr>
      <th>ID</th>
      <td><?= $staff['id'] ?></td>
    </tr>
    <tr>
      <th>Name</th>
      <td><?= htmlspecialchars($staff['name']) ?></td>
    </tr>
    <tr>
      <th>Email</th>
      <td><?= htmlspecialchars($staff['email']) ?></td>
    </tr>
    <tr>
      <th>Role</th>
      <td><?= ucfirst($staff['role']) ?></td>
    </tr>
    <tr>
      <th>Date Registered</th>
      <td><?= $staff['date_registered'] ?></td>
    </tr>
  </table>

  <div class="w3-center">
    <a href="staff_manage.php" class="w3-button btn-back">⬅ Back</a>
  </div>
</div>

</body>
</html>
<?php $conn->close(); ?>
