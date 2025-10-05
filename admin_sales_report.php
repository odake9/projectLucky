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
    .back-btn { display: inline-block; margin-bottom: 20px; padding: 10px 15px; background: #ff9aa2; color: #fff; border-radius: 8px; text-decoration: none; }
    .back-btn:hover { background: #ff6f91; }
    .loading { text-align: center; color: #888; font-style: italic; margin-top: 20px; }
  </style>
</head>
<body>
  <a href="admin.php" class="back-btn">← Back to Dashboard</a>
  <h1>📊 Sales Report</h1>

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

    // Load immediately
    loadSalesData();

    // Refresh every 10 seconds
    setInterval(loadSalesData, 10000);
  </script>
</body>
</html>
