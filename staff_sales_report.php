<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report - Milk Tea Shop</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: "Poppins", sans-serif;
      background: linear-gradient(to bottom right, #fffaf5, #f8efe6);
      color: #4b3b2f;
      margin: 0;
      padding: 30px;
    }

    /* Back Button */
    .back-btn {
      display: inline-block;
      background: #d4a373;
      color: white;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 10px;
      font-weight: 500;
      transition: background 0.3s ease;
      margin-bottom: 20px;
    }
    .back-btn:hover {
      background: #b97a56;
    }

    /* Page Title */
    h1 {
      text-align: center;
      color: #5a3e2b;
      font-size: 2rem;
      font-weight: 600;
      margin-bottom: 25px;
    }

    /* Container */
    #sales-container {
      max-width: 900px;
      margin: 0 auto;
    }

    /* Order Card */
    .order-card {
      background: #ffffff;
      border-radius: 15px;
      box-shadow: 0 6px 15px rgba(90, 62, 43, 0.1);
      padding: 20px 25px;
      margin-bottom: 25px;
      transition: transform 0.2s ease, box-shadow 0.3s ease;
    }
    .order-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 18px rgba(90, 62, 43, 0.15);
    }

    /* Order Header */
    .order-header {
      font-weight: 600;
      color: #6b4f4f;
      font-size: 1.1rem;
      margin-bottom: 12px;
      border-bottom: 2px solid #f2e2d3;
      padding-bottom: 5px;
    }

    /* Item List */
    ul {
      list-style: none;
      padding: 0;
      margin: 0 0 10px 0;
    }
    li {
      padding: 8px 0;
      border-bottom: 1px dashed #e9d7c3;
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #4b3b2f;
    }
    li span {
      color: #5a3e2b;
    }

    /* Totals */
    .order-total {
      text-align: right;
      font-weight: 600;
      color: #c07f56;
      margin-top: 10px;
    }
    .grand-total {
      text-align: right;
      font-weight: 700;
      color: #9b5c38;
      font-size: 1.2rem;
      margin-top: 25px;
      border-top: 2px solid #f2e2d3;
      padding-top: 10px;
    }

    /* Loading Message */
    .loading {
      text-align: center;
      color: #8b7b6b;
      font-style: italic;
      margin-top: 20px;
      font-size: 1rem;
    }
  </style>
</head>
<body>

  <a href="staff.php" class="back-btn">← Back to Dashboard</a>
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
        document.getElementById('sales-container').innerHTML =
          "<p style='color:#b33a3a; text-align:center;'>⚠️ Failed to load sales data.</p>";
      }
    }

    // Load immediately
    loadSalesData();

    // Refresh every 10 seconds
    setInterval(loadSalesData, 10000);
  </script>

</body>
</html>
