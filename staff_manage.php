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

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fbe8e1, #f7d9c4, #fceee4);
      margin: 0;
      padding: 0;
      color: #5c3b28;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      text-align: center;
      margin: 40px 0 25px;
      color: #5c3b28;
      font-size: 34px;
      font-weight: 600;
      text-shadow: 0 2px 4px rgba(0,0,0,0.1);
      animation: fadeInDown 0.8s ease-in-out;
    }

    /* ====== FIXED BUTTON SECTION ====== */
    .top-btns {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-top: 30px;
      margin-bottom: 30px;
      width: 100%;
    }

    .btn {
      background: #c17856;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s ease;
      box-shadow: 0 4px 10px rgba(193,120,86,0.3);
      font-size: 16px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
    }

    .btn:hover {
      background: #a9643b;
      transform: translateY(-2px);
    }

    .btn.red { background: #e57373; }
    .btn.red:hover { background: #c62828; }

    .btn.green { background: #81c784; }
    .btn.green:hover { background: #388e3c; }

    .container {
      width: 90%;
      max-width: 1000px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      padding: 30px;
      animation: fadeInUp 0.8s ease-in-out;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
    }

    th, td {
      padding: 14px;
      text-align: center;
      font-size: 16px;
    }

    th {
      background: #c17856;
      color: #fff;
      letter-spacing: 1px;
    }

    tr:nth-child(even) {
      background: #fff5ee;
    }

    tr:hover {
      background: #ffe8d6;
      transition: background 0.3s;
    }

    td a {
      text-decoration: none;
      color: #fff;
      padding: 6px 14px;
      border-radius: 8px;
      margin: 0 4px;
      display: inline-block;
      transition: all 0.3s;
    }

    td a.view { background: #81c784; }
    td a.view:hover { background: #4caf50; }

    td a.edit { background: #f4a261; }
    td a.edit:hover { background: #e76f51; }

    td a.delete { background: #e57373; }
    td a.delete:hover { background: #c62828; }

    footer {
      margin-top: 40px;
      text-align: center;
      color: #5c3b28;
      opacity: 0.8;
      font-size: 14px;
    }

    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(15px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 768px) {
      .btn {
        width: 80%;
        font-size: 14px;
      }
    }
  </style>
</head>
<body>

  <!-- ✅ Top Buttons (Centered + Responsive) -->
  <div class="top-btns">
    <a href="admin.php" class="btn red">🏠 Back to Dashboard</a>
    <a href="add_staff.php" class="btn green">➕ Add New Staff</a>
  </div>

  <h1>👨‍💼 Staff Management</h1>

  <div class="container">
    <table>
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
              <a href="view_staff.php?id=<?= $row['id'] ?>" class="view">👁 View</a>
              <a href="edit_staff.php?id=<?= $row['id'] ?>" class="edit">✏️ Edit</a>
              <a href="staff_manage.php?delete=<?= $row['id'] ?>" class="delete" onclick="return confirm('⚠️ Are you sure you want to remove this staff?')">🗑 Remove</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="6" style="color:red;">No staff found</td>
        </tr>
      <?php endif; ?>
    </table>
  </div>

  <footer>© 2025 Lucky Milk Tea — Admin Panel</footer>

</body>
</html>
<?php $conn->close(); ?>
