<?php
session_start();
require 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

/* TARIKH FILTER */
$from = $_GET['from'] ?? date('Y-m-01');
$to   = $_GET['to'] ?? date('Y-m-d');

/* QUERY CHARTS / SUMMARY */
$result = mysqli_query($conn, "
    SELECT 
        p.product_name,
        COALESCE(SUM(s.quantity),0) AS total_sold,
        COALESCE((p.price - p.cost) * SUM(s.quantity),0) AS profit,
        MAX(s.sale_date) AS last_sale
    FROM products p
    LEFT JOIN sales s 
        ON s.product_id = p.id
        AND s.sale_date BETWEEN '$from' AND '$to'
    GROUP BY p.id, p.product_name, p.price, p.cost
    ORDER BY p.product_name ASC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales Summary | Hurricane</title>

<link rel="stylesheet" href="style.css">
<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<?php include 'sidebar.php'; ?>

<div class="main">
    <h1>Sales Summary</h1>

    <!-- FILTER TARIKH -->
    <form method="GET" style="max-width:400px;margin-bottom:20px;">
        <label>From</label>
        <input type="date" name="from" value="<?= $from ?>" required>

        <label>To</label>
        <input type="date" name="to" value="<?= $to ?>" required>

        <button type="submit">Filter</button>
    </form>


    <!-- TABLE -->
    <div class="table-card">
        <table>
            <tr>
                <th>Product</th>
                <th>Total Sold</th>
                <th>Profit (RM)</th>
                <th>Last Sale Date</th>
            </tr>

            <?php 
            $grand_profit = 0;
            while ($row = mysqli_fetch_assoc($result)) {
                $grand_profit += $row['profit'];
            ?>
            <tr>
                <td><?= htmlspecialchars($row['product_name']) ?></td>
                <td><?= $row['total_sold'] ?></td>
                <td><?= number_format($row['profit'], 2) ?></td>
                <td>
                    <?= $row['last_sale'] 
                        ? date('d M Y', strtotime($row['last_sale'])) 
                        : '-' ?>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <td><strong>TOTAL PROFIT</strong></td>
                <td></td>
                <td><strong>RM <?= number_format($grand_profit, 2) ?></strong></td>
                <td></td>
            </tr>
        </table>
    </div>
</div>

</body>
</html>