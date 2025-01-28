<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_login();

// 获取用户信息
$user = Database::prepare("
    SELECT username, email, created_at 
    FROM users 
    WHERE id = ?
")->execute([$_SESSION['user_id']])->fetch();
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户信息</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/user_header.php'; ?>
    
    <div class="profile-container">
        <h2>用户信息</h2>
        <div class="profile-info">
            <p><strong>用户名：</strong><?= htmlspecialchars($user['username']) ?></p>
            <p><strong>邮箱：</strong><?= htmlspecialchars($user['email']) ?></p>
            <p><strong>注册时间：</strong><?= $user['created_at'] ?></p>
        </div>
    </div>
</body>
</html>