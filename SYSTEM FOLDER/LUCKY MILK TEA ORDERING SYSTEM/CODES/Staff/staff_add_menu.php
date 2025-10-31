<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','staff'])) {
    header("Location: login.html");
    exit();
}

$servername = "localhost";
$username = "root"; 
$password = "";     
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $_POST['price'];
    $category = $conn->real_escape_string($_POST['category']);

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
  <title>Add Menu Item - Lucky Milk Tea</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #fdf6ec, #f7e3c6);
      font-family: 'Poppins', sans-serif;
      padding: 50px 20px;
      color: #4b2e19;
    }

    .back-link {
      display: inline-block;
      margin: 0 auto 30px auto;
      text-align: center;
      background: #c17856;
      color: white;
      padding: 10px 20px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s ease;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .back-link:hover {
      background: #a9643b;
      transform: translateY(-2px);
      text-decoration: none;
    }

    .form-container {
      background: #fff;
      max-width: 650px;
      margin: auto;
      padding: 40px 35px;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.1);
      border: 2px solid #f1e0c6;
    }

    h2 {
      text-align: center;
      margin-bottom: 30px;
      color: #6b4f3b;
      font-weight: 600;
    }

    label {
      color: #5c3b28;
      font-weight: 500;
    }

    .form-control {
      border-radius: 10px;
      border: 1px solid #d2b48c;
      box-shadow: none;
      padding: 10px 12px;
      font-size: 15px;
      color: #4b2e19;
    }

    .form-control:focus {
      border-color: #b68c5a;
      box-shadow: 0 0 6px rgba(182,140,90,0.4);
    }

    .btn-brown {
      background: #c17856;
      color: white;
      border-radius: 10px;
      border: none;
      padding: 10px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-brown:hover {
      background: #a9643b;
      transform: translateY(-2px);
    }

    .btn-cancel {
      background: #e0c097;
      color: #4b2e19;
      border-radius: 10px;
      border: none;
      padding: 10px;
      font-weight: 500;
      transition: 0.3s ease;
    }

    .btn-cancel:hover {
      background: #cfb183;
    }

    .btn-block {
      margin-top: 10px;
    }

    .top-container {
      text-align: center;
    }
  </style>
</head>
<body>

  <div class="top-container">
    <a href="staff_menu.php" class="back-link">⬅️ Back to Menu Management</a>
  </div>

  <div class="form-container">
    <h2>➕ Add New Menu Item</h2>

    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Drink Name:</label>
        <input type="text" name="name" class="form-control" placeholder="Enter drink name" required>
      </div>

      <div class="form-group">
        <label>Description:</label>
        <textarea name="description" class="form-control" placeholder="Describe your drink..." required></textarea>
      </div>

      <div class="form-group">
        <label>Price (RM):</label>
        <input type="number" name="price" step="0.01" class="form-control" placeholder="e.g. 6.90" required>
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

      <button type="submit" class="btn btn-brown btn-block">💾 Save Menu Item</button>
      <a href="staff_menu.php" class="btn btn-cancel btn-block">❌ Cancel</a>
    </form>
  </div>

</body>
</html>
