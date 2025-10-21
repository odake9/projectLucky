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
        $error = "❌ Failed to send feedback. Please try again.";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us - Lucky Milk Tea</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <style>
  body {
    font-family: "Poppins", sans-serif;
    background: url('homeimage.jpg') center/cover no-repeat fixed; /* ✅ hero background */
    color: #3c2f2f;
    margin: 0;
    padding: 0;
  }

  /* Overlay to darken image slightly for readability */
  .overlay {
    background: rgba(255, 248, 240, 0.92);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }

  /* Header */
  header {
    background: rgba(255, 255, 255, 0.9);
    border-bottom: 1px solid #e5ddd2;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 1rem 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    position: sticky;
    top: 0;
    z-index: 1000;
  }

  .logo {
    font-size: 1.8rem;
    font-weight: 600;
    color: #b68c5a;
    text-align: center;
  }

  .back-btn {
    position: absolute;
    left: 20px;
    background: #b68c5a;
    color: white;
    padding: 0.6rem 1.2rem;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.9rem;
    transition: background 0.3s ease, transform 0.2s ease;
  }

  .back-btn:hover {
    background: #a47b48;
    transform: translateY(-2px);
  }

  /* Main Section */
  main {
    max-width: 800px;
    margin: 4rem auto;
    background: #ffffff;
    padding: 2.5rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    animation: fadeIn 0.6s ease-in-out;
  }

  main h2 {
    color: #b68c5a;
    font-size: 1.5rem;
    margin-bottom: 0.8rem;
  }

  main p {
    font-size: 1rem;
    color: #5a4a3b;
    line-height: 1.6;
  }

  .contact-info {
    margin-bottom: 2rem;
    border-left: 4px solid #b68c5a;
    padding-left: 1rem;
  }

  .contact-info a {
    color: #b68c5a;
    text-decoration: none;
  }

  .contact-info a:hover {
    text-decoration: underline;
  }

  /* Form */
  form {
    margin-top: 1.5rem;
  }

  form label {
    display: block;
    font-weight: 500;
    color: #3c2f2f;
    margin-bottom: 0.4rem;
  }

  form input,
  form textarea {
    width: 100%;
    padding: 0.8rem;
    border: 1px solid #e0d7ce;
    border-radius: 10px;
    font-size: 1rem;
    margin-bottom: 1rem;
    background: #faf8f5;
    transition: border 0.3s ease, background 0.3s ease;
  }

  form input:focus,
  form textarea:focus {
    outline: none;
    border-color: #b68c5a;
    background: #fff;
  }

  button {
    background: #b68c5a;
    color: white;
    border: none;
    padding: 0.8rem 1.8rem;
    font-size: 1rem;
    border-radius: 25px;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.2s ease;
  }

  button:hover {
    background: #a47b48;
    transform: translateY(-2px);
  }

  .message {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1rem;
  }

  .success {
    background: #e6f4ea;
    color: #276749;
  }

  .error {
    background: #fde8e8;
    color: #b91c1c;
  }

  /* Footer */
  footer {
    background: rgba(255,255,255,0.9);
    border-top: 1px solid #e5ddd2;
    text-align: center;
    padding: 1.5rem;
    color: #6d5c4a;
    margin-top: auto;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
</head>

<body>

  <!-- Header -->
  <header>
    <a href="home.html" class="back-btn"><i class="fa fa-arrow-left"></i> Back</a>
    <div class="logo">Lucky Milk Tea</div>
  </header>

  <!-- Main Content -->
  <main>
    <div class="contact-info">
      <h2><i class="fa fa-store"></i> Visit Us</h2>
      <p>📍 123 Milk Tea Street, Bubble City</p>
      <p>📞 <a href="tel:+60145400259">+60145400259</a></p>
      <p>📧 <a href="mailto:luckymilktea88@gmail.com">luckymilktea88@gmail.com</a></p>
    </div>

    <h2><i class="fa fa-comments"></i> Send Us Your Feedback</h2>

    <?php if (!empty($success)) echo "<p class='message success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='message error'>$error</p>"; ?>

    <form method="POST" action="">
      <label for="title">Title</label>
      <input type="text" id="title" name="title" required>

      <label for="name">Your Name</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Your Email</label>
      <input type="email" id="email" name="email" required>

      <label for="message">Your Feedback</label>
      <textarea id="message" name="message" rows="5" required></textarea>

      <button type="submit"><i class="fa fa-paper-plane"></i> Send Message</button>
    </form>
  </main>

  <!-- Footer -->
  <footer>
    © 2025 Lucky Milk Tea — Crafted with love, served with joy.
  </footer>

</body>
</html>
