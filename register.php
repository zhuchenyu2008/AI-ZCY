<?php
require 'includes/db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        header('Location: login.php');
        exit;
    } catch (PDOException $e) {
        $error = "用户名已存在！";
    }
}
?>
<!DOCTYPE html>
<html>
<body>
    <form method="POST">
        <input type="text" name="username" placeholder="用户名" required>
        <input type="password" name="password" placeholder="密码" required>
        <button type="submit">注册</button>
        <?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>
    </form>
</body>
</html>