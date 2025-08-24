<?php
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "milk_tea_shop");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$email = $_POST['email'] ?? '';
$pass = $_POST['password'] ?? '';

if (!empty($email) && !empty($pass)) {
    // Check if email exists
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Verify password
        if (password_verify($pass, $row['password'])) {
            // Save user info in session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            // Redirect to staff.html
            header("Location: admin.html");
            exit();
        } else {
            echo "❌ Wrong password.";
        }
    } else {
        echo "❌ No such user.";
    }
} else {
    echo "⚠️ Please enter both email and password.";
}

$conn->close();
?>
<?php
session_start();
$_SESSION['email'] = $email; // store user/admin email after successful login
header("Location: dashboard.php"); // redirect to dashboard
exit;
?>