<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report</title>
  <style>
    body { font-family: "Poppins", sans-serif; background-color: #fff8f0; padding: 20px; }
    h1 { text-align: center; color: #6b4f4f; margin-bottom: 30px; }
    .order-card {
      background: #ffffff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 25px;
      padding: 20px;
    }
    .order-header { font-weight: bold; color: #6b4f4f; font-size: 1.1rem; margin-bottom: 10px; }
    ul { list-style: none; padding-left: 0; margin: 0 0 10px 0; }
    li { padding: 6px 0; border-bottom: 1px dashed #ddd; display: flex; justify-content: space-between; }
    li span { color: #555; }
    .order-total { text-align: right; font-weight: bold; color: #d46a6a; margin-top: 8px; }
    .grand-total { text-align: right; font-weight: bold; color: #b23a48; font-size: 1.2rem; margin-top: 25px; }
    .back-btn, .pdf-btn {
      display: inline-block;
      margin-bottom: 20px;
      padding: 10px 15px;
      background: #ff9aa2;
      color: #fff;
      border-radius: 8px;
      text-decoration: none;
    }
    .pdf-btn { background: #ffb347; margin-left: 10px; }
    .back-btn:hover { background: #ff6f91; }
    .pdf-btn:hover { background: #ff9f1c; }
    .loading { text-align: center; color: #888; font-style: italic; margin-top: 20px; }
  </style>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>
  <a href="staff.php" class="back-btn">← Back to Dashboard</a>
  <h1>📊 Sales Report</h1>
  <button onclick="downloadPDF()" class="pdf-btn">📄 Download PDF</button>

  <div id="sales-container">
    <div class="loading">Loading latest sales data...</div>
  </div>

  <script>
    async function loadSalesData() {
      try {
        const response = await fetch('fetch_sales_data.php');
        const data = await response.text();
        document.getElementById('sales-container').innerHTML = data;
      } catch (error) {
        document.getElementById('sales-container').innerHTML = "<p style='color:red;'>Failed to load sales data.</p>";
      }
    }

    loadSalesData();
    setInterval(loadSalesData, 10000);
  </script>
  <script>
    async function downloadPDF() {
      const { jsPDF } = window.jspdf;
      const report = document.getElementById("sales-container");

  // Capture the report section
  const canvas = await html2canvas(report, { scale: 2 });
  const imgData = canvas.toDataURL("image/png");

  // Create PDF
  const pdf = new jsPDF("p", "mm", "a4");
  const pageWidth = pdf.internal.pageSize.getWidth();
  const imgWidth = pageWidth - 20;
  const imgHeight = (canvas.height * imgWidth) / canvas.width;

  pdf.addImage(imgData, "PNG", 10, 10, imgWidth, imgHeight);
  pdf.save("Sales_Report.pdf");
}
</script>

</body>
</html>
