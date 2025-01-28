<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'login';
    $username = sanitize_input($_POST['username']);
    $password = $_POST['password'];
    
    if ($action === 'login') {
        if (login($username, $password)) {
            header("Location: ../index.php");
            exit;
        }
        $error = '用户名或密码错误';
    } elseif ($action === 'register') {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        if (register($username, $password, $email)) {
            login($username, $password);
            header("Location: ../index.php");
            exit;
        }
        $error = '注册失败（用户名已存在）';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>登录/注册</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>AI对话平台</h2>
        <form method="POST" class="login-form">
            <div class="tabs">
                <button type="button" class="tab active" data-tab="login">登录</button>
                <button type="button" class="tab" data-tab="register">注册</button>
            </div>
            
            <div id="login-form" class="tab-content active">
                <input type="hidden" name="action" value="login">
                <input type="text" name="username" placeholder="用户名" required>
                <input type="password" name="password" placeholder="密码" required>
                <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
                <button type="submit">登录</button>
            </div>

            <div id="register-form" class="tab-content">
                <input type="hidden" name="action" value="register">
                <input type="text" name="username" placeholder="用户名" required>
                <input type="email" name="email" placeholder="邮箱" required>
                <input type="password" name="password" placeholder="密码" required>
                <?php if($error): ?><div class="error"><?= $error ?></div><?php endif; ?>
                <button type="submit">注册</button>
            </div>
        </form>
    </div>
    <script src="../static/js/login.js"></script>
</body>
</html>