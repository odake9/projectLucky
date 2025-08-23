<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
$sql = "SELECT name, role, date_registered FROM staff ORDER BY date_registered DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
<div class="container">
  <h2>👩‍💻 Staff List</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Staff Name</th>
        <th>Role</th>
        <th>Date Registered</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo $row['name']; ?></td>
          <td><?php echo $row['role']; ?></td>
          <td><?php echo $row['date_registered']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
</body>
</html>

<?php $conn->close(); ?>
