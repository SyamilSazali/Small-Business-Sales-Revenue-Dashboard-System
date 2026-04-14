<?php
session_start();
require 'config.php';
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

if (isset($_POST['add'])) {
mysqli_query($conn,"INSERT INTO products(product_name,price,category)
VALUES('$_POST[name]','$_POST[price]','$_POST[category]')");

echo "<script>alert('Product added successfully'); window.location='products.php';</script>";
}

if (isset($_GET['delete'])) {
mysqli_query($conn,"DELETE FROM products WHERE id=$_GET[delete]");
header("Location: products.php");
}

$products=mysqli_query($conn,"SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'sidebar.php'; ?>

<div class="main">
<h1>Manage Products</h1>

<div class="card">
<form method="POST">
<input name="name" placeholder="Product Name" required>
<input type="number" step="0.01" name="price" placeholder="Price (RM)" min="0" required>
<input name="category" placeholder="Category" required>
<button name="add">Add Product</button>
</form>
</div>

<div class="table-card">
<table>
<tr><th>ID</th><th>Product</th><th>Price</th><th>Category</th><th>Action</th></tr>
<?php while($r=mysqli_fetch_assoc($products)){ ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= $r['product_name'] ?></td>
<td><?= $r['price'] ?></td>
<td><?= $r['category'] ?></td>
<td>
<a class="delete" 
href="?delete=<?= $r['id'] ?>" 
onclick="return confirm('Are you sure you want to delete this product?')">
Delete
</a>
</td>
</tr>
<?php } ?>
</table>
</div>
</div>

</body>
</html>