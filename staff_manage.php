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

// ===== Delete Staff =====
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id=$id");
    echo "<script>alert('✅ Staff removed successfully!'); window.location='staff_manage.php';</script>";
}

// ===== Fetch All Staff =====
$result = $conn->query("SELECT * FROM users ORDER BY date_registered DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Staff - Lucky Milk Tea</title>

  <!-- W3.CSS Framework -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #fdf6ec;
      margin: 0;
      padding: 20px;
      color: #333;
    }
    h1 {
      text-align: center;
      margin: 20px 0;
      color: #6d4c41;
    }
    .top-btns {
      margin-bottom: 20px;
      text-align: center;
    }
    .w3-btn {
      border-radius: 8px;
      font-weight: 500;
      margin: 4px;
    }
    table {
      background: #fff;
    }
    th {
      background: #f4a261 !important;
      color: #fff;
      text-align: center;
    }
    td {
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="top-btns">
    <a href="admin.php" class="w3-button w3-red">🏠 Back to Dashboard</a>
    <a href="add_staff.php" class="w3-button w3-green">➕ Add New Staff</a>
  </div>

  <h1 class="w3-animate-top">👨‍💼 Staff Management</h1>

  <div class="w3-responsive">
    <table class="w3-table-all w3-hoverable w3-card-4">
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Date Registered</th>
        <th>Actions</th>
      </tr>
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= ucfirst($row['role']) ?></td>
            <td><?= $row['date_registered'] ?></td>
            <td>
              <!-- View Button -->
              <a href="view_staff.php?id=<?= $row['id'] ?>" class="w3-button w3-blue w3-round">👁 View</a>

              <!-- Remove Button -->
              <a href="staff_manage.php?delete=<?= $row['id'] ?>" 
                 class="w3-button w3-red w3-round" 
                 onclick="return confirm('⚠️ Are you sure to remove this staff?')">🗑 Remove</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" style="text-align:center; color:red;">No staff found</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

</body>
</html>
<?php $conn->close(); ?>
