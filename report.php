<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

$result = mysqli_query($conn, "
    SELECT 
        p.product_name,
        p.image,
        COALESCE(SUM(s.quantity),0) AS total_sold,
        COALESCE((p.price - p.cost) * SUM(s.quantity),0) AS profit,
        MAX(s.sale_date) AS last_sale
    FROM products p
    LEFT JOIN sales s 
        ON s.product_id = p.id
        AND s.sale_date BETWEEN '$from' AND '$to'
    GROUP BY p.id, p.product_name, p.price, p.cost, p.image
    ORDER BY total_sold DESC
");

$labels = [];
$data = [];
$grand_profit = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $labels[] = $row['product_name'];
    $data[] = $row['total_sold'];
    $grand_profit += $row['profit'];
}

mysqli_data_seek($result, 0);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Business Report | Hurricane</title>

<link rel="stylesheet" href="style.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
.product-img{
    width:65px;
    height:65px;
    object-fit:cover;
    border-radius:10px;
    border:2px solid #eee;
}
.report-header{
    margin-bottom:20px;
}
.report-header h1{
    margin-bottom:5px;
}
.filter-box{
    background:white;
    padding:18px;
    border-radius:10px;
    width:420px;
    box-shadow:0 5px 15px rgba(0,0,0,0.1);
}
.chart-box{
    background:white;
    padding:25px;
    border-radius:12px;
    margin-top:30px;
}
.table-card{
    background:white;
    padding:20px;
    border-radius:12px;
    margin-top:20px;
}
</style>
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main">

<div class="report-header">
<h1>Business Sales Report</h1>
<p>Monitor product performance and profit</p>
</div>

<!-- FILTER -->
<div class="filter-box">
<form method="GET">
<label>From</label>
<input type="date" name="from" value="<?= $from ?>" required>

<label>To</label>
<input type="date" name="to" value="<?= $to ?>" required>

<button type="submit">Apply Filter</button>
</form>
</div>

<!-- TABLE -->
<div class="table-card">
<table>
<tr>
<th>Image</th>
<th>Product</th>
<th>Total Sold</th>
<th>Profit (RM)</th>
<th>Last Sale</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>

<td>
<?php if(!empty($row['image'])) { ?>
<img src="image/<?= $row['image'] ?>" class="product-img">
<?php } else { echo "-"; } ?>
</td>

<td><?= htmlspecialchars($row['product_name']) ?></td>
<td><?= $row['total_sold'] ?></td>
<td>RM <?= number_format($row['profit'],2) ?></td>
<td>
<?= $row['last_sale'] 
? date('d M Y', strtotime($row['last_sale'])) 
: '-' ?>
</td>

</tr>
<?php } ?>

<tr>
<td></td>
<td><strong>TOTAL PROFIT</strong></td>
<td></td>
<td><strong>RM <?= number_format($grand_profit,2) ?></strong></td>
<td></td>
</tr>

</table>
</div>

<br>

<a href="report_pdf.php?from=<?= $from ?>&to=<?= $to ?>" target="_blank">
<button style="width:250px">Generate PDF Report</button>
</a>

<!-- CHART -->
<div class="chart-box">
<h3>Sales Distribution</h3>
<canvas id="salesChart"></canvas>
</div>

</div>

<script>
new Chart(document.getElementById('salesChart'), {
type: 'bar',
data: {
labels: <?= json_encode($labels) ?>,
datasets: [{
label: 'Total Sold',
data: <?= json_encode($data) ?>,
backgroundColor: '#3b82f6',
borderRadius:6
}]
},
options: {
plugins:{
legend:{display:false}
},
scales:{
y:{
beginAtZero:true,
ticks:{color:'#111'}
},
x:{
ticks:{color:'#111'}
}
}
}
});
</script>

</body>
</html>