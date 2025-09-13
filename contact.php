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

// If form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title   = $_POST['title'];
    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $message = $_POST['message'];

    // ✅ Save feedback into database
    $stmt = $conn->prepare("INSERT INTO feedback (title, name, email, message, date_submitted) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $title, $name, $email, $message);

    if ($stmt->execute()) {
        $success = "✅ Feedback sent successfully!";
    } else {
        $error = "❌ Failed to save feedback. Please try again.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Milk Tea Shop</title>

  <!-- W3.CSS Framework -->
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <!-- Google Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
    body,h1,h2,h3,h4,h5 {font-family: "Poppins", sans-serif}
    body {
      background: linear-gradient(135deg, #ffe0f0, #fff8f0);
      margin: 0;
      padding: 0;
    }
    header {
      background: #ff6f91;
      color: white;
      padding: 20px;
      text-align: center;
      position: relative;
    }
    header h1 {
      margin: 0;
      font-size: 2rem;
    }
    .back-btn {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      background: #ff9aa2;
      color: white;
      padding: 8px 15px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 14px;
    }
    .back-btn:hover {
      background: #ff3f6c;
    }
    main {
      max-width: 800px;
      margin: 30px auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .contact-info {
      margin-bottom: 30px;
    }
    .contact-info p {
      font-size: 16px;
      margin: 8px 0;
    }
    form .w3-input, form textarea {
      border-radius: 6px;
    }
    button {
      background: #ff6f91;
      border: none;
      color: white;
      padding: 12px 25px;
      font-size: 16px;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease;
    }
    button:hover {
      background: #ff3f6c;
    }
  </style>
</head>
<body>

  <header>
    <a href="home.html" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
    <h1>Contact Us</h1>
  </header>

  <main class="w3-container">
    <div class="contact-info w3-padding">
      <h2><i class="fa fa-store"></i> Our Store</h2>
      <p>📍 123 Milk Tea Street, Bubble City</p>
      <p>📞 <a href="tel:+60145400259">+60145400259</a></p>
      <p>📧 <a href="mailto:luckymilktea88@gmail.com">luckymilktea88@gmail.com</a></p>
    </div>

    <h2><i class="fa fa-comments"></i> Send Us a Feedback</h2>
    <?php if (!empty($success)) echo "<p class='w3-text-green'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='w3-text-red'>$error</p>"; ?>

    <form method="POST" action="" class="w3-container w3-padding">
      <p>
        <label for="title">Title:</label>
        <input class="w3-input w3-border" type="text" id="title" name="title" required>
      </p>
      <p>
        <label for="name">Your Name:</label>
        <input class="w3-input w3-border" type="text" id="name" name="name" required>
      </p>
      <p>
        <label for="email">Your Email:</label>
        <input class="w3-input w3-border" type="email" id="email" name="email" required>
      </p>
      <p>
        <label for="message">Your Feedback:</label>
        <textarea class="w3-input w3-border" id="message" name="message" rows="5" required></textarea>
      </p>
      <button type="submit"><i class="fa fa-paper-plane"></i> Send Message</button>
    </form>
  </main>
</body>
</html>
