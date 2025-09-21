<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','staff'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root"; // change if needed
$password = "";     // change if needed
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);

    // Handle image upload
    $image = "";
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);

        $fileName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
            $image = $fileName;
        }
    }

    // Insert into database
    $sql = "INSERT INTO menu (name, description, price, category, image) 
            VALUES ('$name', '$description', '$price', '$category', '$image')";
    
    if ($conn->query($sql) === TRUE) {
    echo "<script>
        alert('✅ Menu item added successfully!');
        window.location.href = 'admin_menu.php';
    </script>";
    exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Menu Item</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body {
      background: #fff0f5;
      font-family: 'Poppins', sans-serif;
      padding: 40px;
    }
    .form-container {
      background: #fff;
      max-width: 600px;
      margin: auto;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    h2 {
      text-align: center;
      margin-bottom: 25px;
      color: #d63384;
    }
    .btn-pink {
      background: #d63384;
      color: white;
      border-radius: 8px;
    }
    .btn-pink:hover {
      background: #b62d6f;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>➕ Add New Menu Item</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="form-group">
      <label>Drink Name:</label>
      <input type="text" name="name" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Description:</label>
      <textarea name="description" class="form-control" required></textarea>
    </div>

    <div class="form-group">
      <label>Price (RM):</label>
      <input type="number" name="price" step="0.01" class="form-control" required>
    </div>

    <div class="form-group">
      <label>Category:</label>
      <select name="category" class="form-control" required>
        <option value="Signature">Signature</option>
        <option value="Ice Blended">Ice Blended</option>
        <option value="Refreshing">Refreshing</option>
      </select>
    </div>

    <div class="form-group">
      <label>Upload Image:</label>
      <input type="file" name="image" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-pink btn-block">Save Menu Item</button>
    <a href="admin_menu.php" class="btn btn-default btn-block">Cancel</a>
  </form>
</div>

</body>
</html>
