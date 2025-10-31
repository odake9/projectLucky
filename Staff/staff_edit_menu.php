<?php
session_start();
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin','staff'])) {
    header("Location: login.html");
    exit();
}

$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Get menu ID
if (!isset($_GET['id'])) {
    die("⚠️ Menu ID missing!");
}
$id = intval($_GET['id']);

// Fetch existing data
$result = $conn->query("SELECT * FROM menu WHERE id=$id");
if ($result->num_rows == 0) {
    die("⚠️ Menu not found!");
}
$menu = $result->fetch_assoc();

// Handle Update
if (isset($_POST['update_menu'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $imageName = $menu['image']; // keep old image by default

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/menu/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

        $imageName = time() . "_" . basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $imageName;
        move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);
    }

    $stmt = $conn->prepare("UPDATE menu SET name=?, price=?, category=?, image=? WHERE id=?");
    $stmt->bind_param("sdssi", $name, $price, $category, $imageName, $id);

    if ($stmt->execute()) {
        echo "<script>alert('✅ Menu updated successfully!'); window.location.href='admin_menu.php';</script>";
    } else {
        echo "<script>alert('❌ Error updating menu: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Menu</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <style>
    body { background: #ffe6f2; font-family: 'Poppins', sans-serif; }
    .form-container {
      max-width: 500px; margin: 50px auto;
      background: #fff; padding: 20px;
      border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .btn-pink { background: #d63384; color: white; border-radius: 8px; }
    .btn-pink:hover { background: #b62d6f; }
  </style>
</head>
<body>
  <div class="form-container">
    <h2 style="color:#d63384;">✏️ Edit Menu</h2>
    <form method="POST" enctype="multipart/form-data">
      <div class="form-group">
        <label>Menu Name:</label>
        <input type="text" name="name" class="form-control" value="<?php echo $menu['name']; ?>" required>
      </div>
      <div class="form-group">
        <label>Price (RM):</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $menu['price']; ?>" required>
      </div>
      <div class="form-group">
        <label>Category:</label>
        <input type="text" name="category" class="form-control" value="<?php echo $menu['category']; ?>" required>
      </div>
      <div class="form-group">
        <label>Current Image:</label><br>
        <?php if ($menu['image']) { ?>
          <img src="uploads/menu/<?php echo $menu['image']; ?>" width="120" style="border-radius:10px;">
        <?php } else { ?>
          <p>No image uploaded</p>
        <?php } ?>
      </div>
      <div class="form-group">
        <label>Upload New Image:</label>
        <input type="file" name="image" class="form-control">
      </div>
      <button type="submit" name="update_menu" class="btn btn-pink btn-block">💾 Save Changes</button>
      <a href="staff_menu.php" class="btn btn-default btn-block">⬅ Back</a>
    </form>
  </div>
</body>
</html>
