<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['save'])) {
    $name = $_POST['product_name'];
    $price = $_POST['price'];
    $category = $_POST['category'];

    mysqli_query($conn, "INSERT INTO products (product_name, price, category)
                         VALUES ('$name', '$price', '$category')");
    header("Location: products.php");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
</head>
<body>

<h2>Add Product</h2>

<form method="post">
    <label>Product Name</label><br>
    <input type="text" name="product_name" required><br><br>

    <label>Price (RM)</label><br>
    <input type="number" step="0.01" name="price" required><br><br>

    <label>Category</label><br>
    <input type="text" name="category" required><br><br>

    <button type="submit" name="save">Save</button>
</form>

<br>
<a href="products.php">⬅ Back</a>

</body>
</html>