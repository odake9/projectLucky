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
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #fbe8e1, #f7d9c4, #fceee4);
      margin: 0;
      padding: 0;
      color: #5c3b28;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    h1 {
      text-align: center;
      margin: 40px 0 25px;
      font-size: 34px;
      color: #5c3b28;
      font-weight: 600;
    }

    .top-btns {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }

    .btn {
      background: #c17856;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
      box-shadow: 0 4px 10px rgba(193,120,86,0.3);
    }

    .btn:hover { background: #a9643b; transform: translateY(-2px); }

    .btn.green { background: #81c784; }
    .btn.green:hover { background: #388e3c; }

    .container {
      width: 90%;
      max-width: 1000px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
      padding: 30px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      table-layout: fixed;
    }

    th, td {
      padding: 14px;
      text-align: center;
      font-size: 16px;
      word-wrap: break-word;
    }

    th {
      background: #c17856;
      color: #fff;
    }

    tr:nth-child(even) { background: #fff5ee; }
    tr:hover { background: #ffe8d6; }

    /* ===== Dropdown Actions ===== */
    .dropdown {
      position: relative;
      display: inline-block;
    }

    .dropdown-btn {
      background: #c17856;
      color: white;
      border: none;
      border-radius: 8px;
      padding: 8px 16px;
      cursor: pointer;
      transition: 0.3s;
    }

    .dropdown-btn:hover {
      background: #a9643b;
    }

    .dropdown-content {
      display: none;
      position: absolute;
      right: 0;
      background-color: white;
      min-width: 140px;
      box-shadow: 0 8px 16px rgba(0,0,0,0.2);
      border-radius: 10px;
      z-index: 1;
    }

    .dropdown-content a {
      display: block;
      color: #5c3b28;
      padding: 10px 12px;
      text-decoration: none;
      text-align: left;
      border-bottom: 1px solid #eee;
      transition: 0.2s;
    }

    .dropdown-content a:last-child {
      border-bottom: none;
    }

    .dropdown-content a:hover {
      background-color: #fbe8e1;
    }

    .show {
      display: block;
      animation: fadeIn 0.2s ease;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    footer {
      margin: 40px 0;
      color: #5c3b28;
      opacity: 0.8;
    }
  </style>
</head>
<body>

  <div class="top-btns">
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
            <td title="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></td>
            <td title="<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></td>
            <td><?= ucfirst($row['role']) ?></td>
            <td><?= $row['date_registered'] ?></td>
            <td>
              <div class="dropdown">
                <button class="dropdown-btn" onclick="toggleDropdown(event)">...</button>
                <div class="dropdown-content">
                  <a href="view_staff.php?id=<?= $row['id'] ?>">👁 View</a>
                  <a href="edit_staff.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                  <a href="staff_manage.php?delete=<?= $row['id'] ?>" onclick="return confirm('⚠️ Are you sure you want to remove this staff?')">🗑 Remove</a>
                </div>
              </div>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="6" style="color:red;">No staff found</td></tr>
      <?php endif; ?>
    </table>
  </div>

  <footer>© 2025 Lucky Milk Tea — Admin Panel</footer>

  <script>
    function toggleDropdown(event) {
      event.stopPropagation(); // prevent bubbling
      const dropdown = event.currentTarget.nextElementSibling;
      const openDropdowns = document.querySelectorAll('.dropdown-content.show');
      openDropdowns.forEach(d => { if (d !== dropdown) d.classList.remove('show'); });
      dropdown.classList.toggle('show');
    }

    window.onclick = function() {
      document.querySelectorAll('.dropdown-content').forEach(d => d.classList.remove('show'));
    }
  </script>

</body>
</html>
<?php $conn->close(); ?>
