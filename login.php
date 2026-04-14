<?php
session_start();
require 'config.php';

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit();
}

$error = "";

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        $_SESSION['admin'] = $data['username'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login</title>

<style>
* {
    box-sizing: border-box;
}

body {
    margin: 0;
    height: 100vh;
    background: #f5f5f5;
    font-family: Arial, sans-serif;
    display: flex;
    justify-content: center;
    align-items: center;
}

.login-container {
    width: 340px;
    text-align: center;
}

.login-container h2 {
    margin-bottom: 25px;
    font-weight: 600;
    letter-spacing: 1px;
}

.login-box input {
    width: 100%;
    height: 48px;
    padding: 0 14px;
    margin-bottom: 15px;
    border: none;
    background: #e0e0e0;
    font-size: 15px;
}

.login-box button {
    width: 100%;
    height: 48px;
    border: none;
    background: #000;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.login-box button:hover {
    background: #222;
}

.error {
    color: red;
    font-size: 14px;
    margin-bottom: 15px;
}
</style>
</head>

<body>

<div class="login-container">
    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form class="login-box" method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
</div>

</body>
</html>