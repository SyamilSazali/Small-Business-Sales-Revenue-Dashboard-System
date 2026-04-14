<?php
session_start();
require 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

$p = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM products"))['total'] ?? 0;
$s = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) total FROM sales"))['total'] ?? 0;

/* TAMBAH TOTAL PROFIT */
$profit = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT SUM((products.price - products.cost) * sales.quantity) AS total_profit
FROM sales
JOIN products ON sales.product_id = products.id
"))['total_profit'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head><title>Dashboard</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
<h1>Dashboard Overview</h1>
<p>Today: <?= date("d M Y") ?></p>

<div class="card">
<h3>TOTAL PRODUCTS</h3>
<h1><?= $p ?></h1>
</div>

<div class="card">
<h3>TOTAL SALES</h3>
<h1><?= $s ?></h1>
</div>

<div class="card">
<h3>TOTAL PROFIT</h3>
<h1>RM <?= number_format($profit,2) ?></h1>
</div>

</div>

</body>
</html>