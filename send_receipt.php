<?php
require('fpdf/fpdf.php'); // or your FPDF path

$order_id = $_POST['order_id'];
$total    = $_POST['total'];
$email    = $_POST['email'];
$billcode = $_POST['billcode'];

// 1️⃣ Generate PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,"Lucky Milk Tea Receipt",0,1,'C');
$pdf->SetFont('Arial','',12);
$pdf->Ln(5);
$pdf->Cell(0,10,"Order ID: $order_id",0,1);
$pdf->Cell(0,10,"Bill Code: $billcode",0,1);
$pdf->Cell(0,10,"Total: RM ".number_format($total,2),0,1);

$file = "receipt_order_$order_id.pdf";
$pdf->Output('F', $file);

// 2️⃣ Send Email
use PHPMailer\PHPMailer\PHPMailer;
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

$mail = new PHPMailer();
$mail->isSMTP();
$mail->Host = 'nova.ix-dns.com';
$mail->SMTPAuth = true;
$mail->Username = '_mainaccount@luckymilktea.xyz';
$mail->Password = '010259@Lau';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('noreply@luckymilktea.xyz', 'Lucky Milk Tea');
$mail->addAddress($email);
$mail->Subject = 'Your Lucky Milk Tea Receipt';
$mail->Body = "Thank you for your order! Please find your receipt attached.";
$mail->addAttachment($file);

if($mail->send()){
    unlink($file); // delete local PDF
    echo "<h2>✅ Receipt sent!</h2>";
    echo "<p>Redirecting to homepage...</p>";
    echo "<script>setTimeout(()=>{window.location.href='https://www.luckymilktea.xyz/index.html'},3000);</script>";
} else {
    echo "❌ Failed to send receipt: ".$mail->ErrorInfo;
}
?>
