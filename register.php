<?php
// Database connection
$servername = "localhost";
$username = "root"; // default in XAMPP
$password = "";     // default in XAMPP is empty
$dbname = "milk_tea_shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$name = $_POST['name'];
$email = $_POST['email'];
$pass = $_POST['password'];

// Encrypt password
$hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

// Insert into DB
$sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$hashed_pass')";

if ($conn->query($sql) === TRUE) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Registration Successful</title>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: linear-gradient(135deg, #ff7eb3, #ff758c, #ff9770);
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .success-box {
                background: white;
                padding: 40px;
                border-radius: 15px;
                text-align: center;
                box-shadow: 0 5px 15px rgba(0,0,0,0.3);
                max-width: 400px;
                animation: fadeIn 1s ease-in-out;
            }
            .success-box h2 {
                color: #28a745;
                margin-bottom: 15px;
            }
            .success-box p {
                font-size: 16px;
                color: #444;
                margin-bottom: 25px;
            }
            .btn {
                display: inline-block;
                padding: 12px 25px;
                background: #ff758c;
                color: white;
                font-size: 16px;
                border-radius: 8px;
                text-decoration: none;
                font-weight: bold;
                transition: all 0.3s ease;
            }
            .btn:hover {
                background: #e8436f;
                transform: scale(1.05);
            }
            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(-20px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>
        <div class="success-box">
            <h2>🎉 Registration Successful!</h2>
            <p>Welcome <b><?php echo htmlspecialchars($name); ?></b>, your account has been created successfully.</p>
            <a href="login.html" class="btn">Login Now</a>
            <br><br>
            <a href="home.html" class="btn" style="background:#6c757d;">Go to Home</a>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "❌ Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
