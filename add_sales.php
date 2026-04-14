<?php
session_start();
require 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

if (isset($_POST['save'])) {
mysqli_query($conn,"INSERT INTO sales(product_id,quantity,sale_date)
VALUES('$_POST[p]','$_POST[q]','$_POST[d]')");

echo "<script>alert('Sales added successfully'); window.location='add_sales.php';</script>";
}

$p=mysqli_query($conn,"SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
<title>Add Sales</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
<h1>Add Sales</h1>

<div class="card">
<form method="POST">
<select name="p" required>
<?php while($x=mysqli_fetch_assoc($p)){ ?>
<option value="<?= $x['id'] ?>"><?= $x['product_name'] ?></option>
<?php } ?>
</select>

<input type="number" name="q" placeholder="Quantity" min="1" required>
<input type="date" name="d" required>

<button name="save">Save</button>
</form>
</div>
</div>

</body>
</html>