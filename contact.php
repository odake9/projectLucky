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
        $success = "✅ Feedback saved successfully!";
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
  <link rel="stylesheet" href="contact.css" />
</head>
<body>
  <header>
    <a href="home.html" class="back-btn">← Back to Home</a>
    <h1>Contact Us</h1>
  </header>

  <main>
    <div class="contact-info">
      <h2>Our Store</h2>
      <p>📍 123 Milk Tea Street, Bubble City</p>
      <p>📞 <a href="tel:+60145400259">+60145400259</a></p>
      <p>📧 <a href="mailto:luckymilktea88@gmail.com">luckymilktea88@gmail.com</a></p>
    </div>

    <h2>Send Us a Feedback</h2>
    <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

    <form method="POST" action="">
      <div>
        <label for="title">Title : </label>
        <input type="text" id="title" name="title" required>
      </div>
      <div>
        <label for="name">Your Name: </label>
        <input type="text" id="name" name="name" required>
      </div>
      <div>
        <label for="email">Your Email: </label>
        <input type="email" id="email" name="email" required>
      </div>
      <div>
        <label for="message">Your Feedback: </label>
        <textarea id="message" name="message" required></textarea>
      </div>
      <button type="submit">Send Message</button>
    </form>
  </main>
</body>
</html>
