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
  <title>Contact & Feedback - Lucky Milk Tea</title>

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: "Poppins", sans-serif;
      background: url('homeimage.jpg') center/cover no-repeat fixed;
      color: #3c2f2f;
    }
    a { text-decoration: none; color: inherit; transition: 0.3s ease; }

    /* --- HEADER --- */
    header {
      position: fixed;
      top: 0; left: 0; right: 0;
      background: #ffffff;
      border-bottom: 1px solid #e5ddd2;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
      z-index: 1000;
    }

    .nav-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0.5rem 2rem;
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 90px;
      position: relative;
    }

    .nav-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      color: #b68c5a;
      font-size: 1.6rem;
      font-weight: 600;
      height: 80px;
    }

    .nav-logo img {
      height: 70px;
      width: auto;
      object-fit: contain;
      display: block;
    }

    nav {
      position: absolute;
      left: 55%;
      transform: translateX(-50%);
    }

    nav ul {
      list-style: none;
      display: flex;
      gap: 2rem;
    }

    nav ul li a {
      color: #5e4b3c;
      font-weight: 400;
      position: relative;
      padding-bottom: 4px;
      transition: color 0.3s ease;
    }

    nav ul li a:hover {
      color: #b68c5a;
    }

    nav ul li a::after {
      content: "";
      position: absolute;
      left: 0; bottom: 0;
      width: 0; height: 2px;
      background: #b68c5a;
      transition: width 0.3s;
    }

    nav ul li a:hover::after {
      width: 100%;
    }

    .nav-actions {
      display: flex;
      align-items: center;
      justify-content: flex-end;
    }

    .nav-actions .nav-btn {
      display: flex;
      align-items: center;
      background: #b68c5a;
      color: white;
      border: none;
      padding: 0.5rem 1.25rem;
      border-radius: 25px;
      font-size: 0.95rem;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }

    .nav-btn i { margin-right: 0.5rem; }

    .nav-btn:hover {
      background: #a47b48;
      transform: translateY(-2px);
    }

    /* --- MAIN CONTENT --- */
    main {
      max-width: 800px;
      margin: 140px auto 4rem auto;
      background: rgba(255,255,255,0.96);
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

    .contact-info a:hover { text-decoration: underline; }

    form { margin-top: 1.5rem; }

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

    .success { background: #e6f4ea; color: #276749; }
    .error { background: #fde8e8; color: #b91c1c; }

    footer {
      background: #fff;
      border-top: 1px solid #e5ddd2;
      text-align: center;
      padding: 1.5rem;
      color: #6d5c4a;
      margin-top: 3rem;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>
</head>

<body>
  <!-- Navbar (same as homepage) -->
  <header>
    <div class="nav-container">
      <a href="home.html" class="nav-logo">
        <img src="logo.png" alt="Lucky Logo">
        <span>Lucky Milk Tea</span>
      </a>

      <nav>
        <ul>
          <li><a href="home.html">Home</a></li>
          <li><a href="about.html">About</a></li>
          <li><a href="menu.php">Menu</a></li>
          <li><a href="contact.php">Contact & Feedback</a></li>
          <li><a href="customer_view_order.php">Order Status</a></li>
        </ul>
      </nav>

      <div class="nav-actions">
        <a href="login.html" class="nav-btn"><i class="fa fa-user"></i> Login</a>
      </div>
    </div>
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

  <footer>
    © 2025 Lucky Milk Tea — Crafted with love, served with joy.
  </footer>
</body>
</html>
