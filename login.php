<?php
require 'includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if (loginUser($username, $password)) {
        header('Location: chat.php');
        exit;
    } else {
        $error = "用户名或密码错误！";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>登录</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <form method="POST">
        <input type="text" name="username" placeholder="用户名" required>
        <input type="password" name="password" placeholder="密码" required>
        <button type="submit">登录</button>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
    <a href="register.php">注册新账号</a>
</body>
</html>