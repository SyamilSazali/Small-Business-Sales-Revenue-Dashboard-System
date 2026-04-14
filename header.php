<?php
require_once "config.php";
require_once "includes/auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hurracane Admin</title>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<!-- Main CSS -->
<link rel="stylesheet" href="assets/style.css">
</head>
<body>

<div class="layout">

<!-- SIDEBAR -->
<aside class="sidebar">
  <h2 class="logo">HURRACANE</h2>

  <ul class="menu">
    <li><a href="dashboard.php"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
    <li><a href="products.php"><i class="fa-solid fa-box"></i> Products</a></li>
    <li><a href="add_sales.php"><i class="fa-solid fa-plus"></i> Add Sales</a></li>
    <li><a href="charts.php"><i class="fa-solid fa-chart-pie"></i> Charts</a></li>
    <li><a href="report.php"><i class="fa-solid fa-file-lines"></i> Report</a></li>
    <li><a href="logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
  </ul>
</aside>

<!-- MAIN CONTENT -->
<main class="content">