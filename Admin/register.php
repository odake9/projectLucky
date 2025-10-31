<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
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
    <title>Registration Successful - Lucky Milk Tea</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #fbe8e1, #f7d9c4, #fceee4);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #5c3b28;
        }

        .success-box {
            background: #fffdf8;
            border: 2px solid #f1e2d3;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            padding: 45px 35px;
            text-align: center;
            max-width: 420px;
            animation: fadeIn 1s ease-in-out;
        }

        .success-box img {
            width: 80px;
            height: 80px;
            margin-bottom: 15px;
        }

        .success-box h2 {
            color: #b68c5a;
            font-size: 1.7rem;
            margin-bottom: 10px;
        }

        .success-box p {
            font-size: 1rem;
            color: #6b4e3d;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .btn {
            display: inline-block;
            padding: 12px 28px;
            margin: 6px;
            background-color: #b68c5a;
            color: white;
            font-size: 15px;
            font-weight: 600;
            border-radius: 25px;
            text-decoration: none;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .btn:hover {
            background-color: #a47b48;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #5c3b28;
        }

        .btn-secondary:hover {
            background-color: #3e281b;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            position: absolute;
            bottom: 15px;
            width: 100%;
            text-align: center;
            font-size: 14px;
            color: #5c3b28;
        }
    </style>
</head>
<body>

    <div class="success-box">
        <img src="logo.png" alt="Lucky Logo">
        <h2>🎉 Registration Successful!</h2>
        <p>Welcome <b><?php echo htmlspecialchars($name); ?></b>! <br>Your Lucky Milk Tea account has been created successfully.</p>
        <a href="login.html" class="btn"><i class="fa fa-sign-in-alt"></i> Login Now</a>
        <a href="home.html" class="btn btn-secondary"><i class="fa fa-home"></i> Go to Home</a>
    </div>

    <footer>© 2025 Lucky Milk Tea — Crafted with love, served with joy.</footer>

</body>
</html>
<?php
} else {
    echo "❌ Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?>
