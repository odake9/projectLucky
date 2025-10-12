<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'fpdf/fpdf.php';

// ====== GET DATA ======
$order_id = $_POST['order_id'];
$total = $_POST['total'];
$billcode = $_POST['billcode'];
$email = $_POST['email'];

// ====== 1️⃣ CREATE PDF RECEIPT ======
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Lucky Milk Tea Receipt', 0, 1, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'Order ID: ' . $order_id, 0, 1);
$pdf->Cell(0, 10, 'Bill Code: ' . $billcode, 0, 1);
$pdf->Cell(0, 10, 'Total: RM ' . number_format($total, 2), 0, 1);
$pdf->Cell(0, 10, 'Date: ' . date("Y-m-d H:i:s"), 0, 1);
$pdf->Ln(10);
$pdf->MultiCell(0, 8, "Thank you for purchasing at Lucky Milk Tea!\nEnjoy your drink!", 0, 'C');

// Save PDF
$pdfFile = "receipt_$order_id.pdf";
$pdf->Output('F', $pdfFile);

// ====== 2️⃣ SEND EMAIL ======
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;

    // 🔐 Your Gmail (use an App Password, not your real password)
    $mail->Username = 'yourgmail@gmail.com';
    $mail->Password = 'your_app_password_here';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('yourgmail@gmail.com', 'Lucky Milk Tea');
    $mail->addAddress($email);

    $mail->isHTML(true);
    $mail->Subject = 'Your Lucky Milk Tea Receipt';
    $mail->Body = 'Hi there,<br><br>Thank you for your payment! Attached is your receipt.<br><br>🍹 <b>Lucky Milk Tea</b>';
    $mail->addAttachment($pdfFile);

    $mail->send();
    echo "<h3>✅ Receipt sent successfully to $email</h3>";
    echo "<a href='home.html'>Back to Home</a>";

    unlink($pdfFile); // delete after sending

} catch (Exception $e) {
    echo "❌ Failed to send email. Error: {$mail->ErrorInfo}";
}
?>
