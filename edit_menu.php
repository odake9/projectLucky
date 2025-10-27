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
    // Handle new image upload
if (!empty($_FILES['image']['name'])) {
    $targetDir = "uploads/";
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
  <title>Edit Menu - Lucky Milk Tea</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(to bottom right, #fffaf5, #f8efe6);
      color: #4b3b2f;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 60px 20px;
    }

    .edit-container {
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(90, 62, 43, 0.15);
      padding: 35px;
      max-width: 500px;
      width: 100%;
      text-align: center;
    }

    h2 {
      color: #b68c5a;
      margin-bottom: 25px;
      font-weight: 600;
      font-size: 1.8rem;
    }

    label {
      display: block;
      text-align: left;
      color: #5a3e2b;
      font-weight: 600;
      margin-bottom: 5px;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
      width: 100%;
      padding: 10px 12px;
      margin-bottom: 15px;
      border-radius: 12px;
      border: 1px solid #e4cdb5;
      background: #fffaf5;
      font-size: 0.95rem;
      outline: none;
      transition: 0.3s;
    }

    input:focus {
      border-color: #b68c5a;
      box-shadow: 0 0 5px rgba(182, 140, 90, 0.3);
    }

    img {
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 10px;
    }

    .btn {
      display: inline-block;
      width: 100%;
      padding: 12px;
      border-radius: 25px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 1rem;
      transition: background 0.3s ease, transform 0.2s ease;
      margin-top: 10px;
    }

    .btn-save {
      background: #b68c5a;
      color: #fff;
    }

    .btn-save:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    .btn-back {
      background: #d4a373;
      color: #fff;
    }

    .btn-back:hover {
      background: #b97a56;
      transform: translateY(-2px);
    }

    .image-preview {
      margin-bottom: 15px;
    }
  </style>
</head>

<body>
  <div class="edit-container">
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Menu</h2>
    <form method="POST" enctype="multipart/form-data">
      <label>Menu Name:</label>
      <input type="text" name="name" value="<?php echo $menu['name']; ?>" required>

      <label>Price (RM):</label>
      <input type="number" step="0.01" name="price" value="<?php echo $menu['price']; ?>" required>

      <label>Category:</label>
      <input type="text" name="category" value="<?php echo $menu['category']; ?>" required>

      <label>Current Image:</label>
<div class="image-preview">
  <?php if ($menu['image']) { ?>
    <img src="uploads/<?php echo $menu['image']; ?>" width="120">
  <?php } else { ?>
    <p>No image uploaded</p>
  <?php } ?>
</div>


      <label>Upload New Image:</label>
      <input type="file" name="image">

      <button type="submit" name="update_menu" class="btn btn-save">💾 Save Changes</button>
      <a href="admin_menu.php" class="btn btn-back">⬅ Back</a>
    </form>
  </div>
</body>
</html>
