<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/* GET SALES DATA */
$result = mysqli_query($conn, "
    SELECT products.product_name, SUM(sales.quantity) AS total
    FROM sales
    JOIN products ON sales.product_id = products.id
    GROUP BY products.product_name
");

$labels = [];
$data   = [];

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['product_name'];
    $data[]   = $row['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Chart</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f8;
}
.sidebar {
    width: 220px;
    height: 100vh;
    background: #0f172a;
    color: white;
    position: fixed;
    padding: 20px;
}
.sidebar h2 {
    text-align: center;
}
.sidebar a {
    display: block;
    color: white;
    text-decoration: none;
    padding: 12px;
    margin: 8px 0;
    border-radius: 6px;
}
.sidebar a:hover {
    background: #1e293b;
}
.main {
    margin-left: 240px;
    padding: 40px;
}
.card {
    background: white;
    padding: 30px;
    border-radius: 12px;
    width: 500px;
}
</style>
</head>

<body>

<div class="sidebar">
    <h2>HURRACANE</h2>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="products.php">📦 Products</a>
    <a href="add_sales.php">➕ Add Sales</a>
    <a href="charts.php">📈 Charts</a>
    <a href="report.php">📄 Report</a>
    <a href="logout.php">🚪 Logout</a>
</div>

<div class="main">
    <h1>Sales Chart</h1>

    <div class="card">
        <canvas id="salesChart"></canvas>
    </div>
</div>

<script>
const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($labels) ?>,
        datasets: [{
            data: <?= json_encode($data) ?>,
            backgroundColor: [
                '#0f172a',
                '#1e293b',
                '#334155',
                '#475569',
                '#64748b'
            ]
        }]
    }
});
</script>

</body>
</html>