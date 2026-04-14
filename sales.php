<?php
include 'config.php';

if (isset($_POST['add'])) {
    mysqli_query($conn,
        "INSERT INTO sales(product_id,quantity,sale_date)
         VALUES ('$_POST[product]','$_POST[qty]',CURDATE())"
    );
}

$products = mysqli_query($conn,"SELECT * FROM products");
?>

<h2>Add Sales</h2>
<form method="POST">
<select name="product">
<?php while($p=mysqli_fetch_assoc($products)){ ?>
<option value="<?= $p['id'] ?>"><?= $p['product_name'] ?></option>
<?php } ?>
</select>
<input name="qty" placeholder="Quantity" required>
<button name="add">Save</button>
</form>