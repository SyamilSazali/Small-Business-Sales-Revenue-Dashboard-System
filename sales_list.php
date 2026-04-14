<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales List</title>
</head>
<body>

<h2>Sales List</h2>

<a href="sales.php">➕ Add Sale</a>
<br><br>

<table border="1" cellpadding="8">
<tr>
    <th>Product</th>
    <th>Quantity</th>
    <th>Date</th>
</tr>

<?php
$result = mysqli_query($conn,
    "SELECT products.product_name, sales.quantity, sales.sale_date
     FROM sales
     JOIN products ON sales.product_id = products.id"
);

while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
    <td><?= $row['product_name'] ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['sale_date'] ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="dashboard.php">⬅ Back</a>

</body>
</html>