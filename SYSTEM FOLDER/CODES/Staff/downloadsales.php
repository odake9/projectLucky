<?php
require('fpdf/fpdf.php'); // Make sure you have the FPDF library in a folder named 'fpdf'

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "milk_tea_shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "
  SELECT 
    o.order_id, 
    o.order_date, 
    o.total AS order_total,
    i.name AS product_name,
    i.price,
    i.quantity
  FROM orders o
  JOIN order_items i ON o.order_id = i.order_id
  ORDER BY o.order_date DESC, o.order_id
";

$result = $conn->query($sql);

// Group data by order
$orderItems = [];
$grandTotal = 0;
if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $orderId = $row['order_id'];
    if (!isset($orderItems[$orderId])) {
      $orderItems[$orderId] = [
        'date' => $row['order_date'],
        'total' => $row['order_total'],
        'items' => []
      ];
      $grandTotal += $row['order_total'];
    }
    $orderItems[$orderId]['items'][] = [
      'name' => $row['product_name'],
      'price' => $row['price'],
      'quantity' => $row['quantity']
    ];
  }
}

// Initialize PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);

// Title
$pdf->Cell(0,10,'Milk Tea Shop - Sales Report',0,1,'C');
$pdf->Ln(5);

$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,'Date Generated: '.date('Y-m-d H:i:s'),0,1,'C');
$pdf->Ln(8);

// Loop through orders
foreach ($orderItems as $orderId => $data) {
  $pdf->SetFont('Arial','B',12);
  $pdf->Cell(0,8,"Order #$orderId  |  {$data['date']}",0,1);
  $pdf->SetFont('Arial','',11);

  // Table header
  $pdf->SetFillColor(240, 230, 210);
  $pdf->Cell(90,8,'Product',1,0,'C',true);
  $pdf->Cell(30,8,'Quantity',1,0,'C',true);
  $pdf->Cell(30,8,'Price (RM)',1,0,'C',true);
  $pdf->Cell(40,8,'Subtotal (RM)',1,1,'C',true);

  foreach ($data['items'] as $item) {
    $subtotal = $item['price'] * $item['quantity'];
    $pdf->Cell(90,8,$item['name'],1);
    $pdf->Cell(30,8,$item['quantity'],1,0,'C');
    $pdf->Cell(30,8,number_format($item['price'],2),1,0,'C');
    $pdf->Cell(40,8,number_format($subtotal,2),1,1,'C');
  }

  // Order total
  $pdf->SetFont('Arial','B',11);
  $pdf->Cell(150,8,'Order Total',1,0,'R',true);
  $pdf->Cell(40,8,'RM '.number_format($data['total'],2),1,1,'C',true);
  $pdf->Ln(5);
}

// Grand total
$pdf->SetFont('Arial','B',13);
$pdf->Cell(150,10,'Grand Total',1,0,'R');
$pdf->Cell(40,10,'RM '.number_format($grandTotal,2),1,1,'C');

$pdf->Output('D','sales_report.pdf'); // Force download
$conn->close();
?>
