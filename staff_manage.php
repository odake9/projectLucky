<?php
session_start();

// ✅ Connect to database
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ✅ Fetch users from DB
$sql = "SELECT id, name, email, role, date_registered FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f9f9f9;
      margin: 0;
      padding: 0;
    }
    h1 {
      text-align: center;
      padding: 20px;
      background: #333;
      color: white;
      margin: 0;
    }
    .container {
      width: 90%;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: center;
    }
    th {
      background: #333;
      color: white;
    }
    tr:nth-child(even) {
      background: #f2f2f2;
    }
    .btn {
      display: inline-block;
      padding: 8px 15px;
      margin: 10px 0;
      background: #28a745;
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
    }
    .btn:hover {
      background: #218838;
    }
  </style>
</head>
<body>
  <h1>Admin Dashboard</h1>
  <div class="container">
    <a href="add_staff.php" class="btn">+ Add New Staff</a>
    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th>Date Registered</th>
      </tr>
      <?php if ($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['name']); ?></td>
            <td><?php echo htmlspecialchars($row['email']); ?></td>
            <td><?php echo htmlspecialchars($row['role']); ?></td>
            <td><?php echo htmlspecialchars($row['date_registered']); ?></td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr><td colspan="5">No users found</td></tr>
      <?php endif; ?>
    </table>
  </div>
</body>
</html>
<?php
$conn->close();
?>
