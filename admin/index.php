<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_admin();

// 获取统计数据
$stats = [
    'total_users' => Database::query('SELECT COUNT(*) FROM users')->fetchColumn(),
    'active_apis' => Database::query('SELECT COUNT(*) FROM api_configs WHERE is_active = 1')->fetchColumn(),
    'total_conversations' => Database::query('SELECT COUNT(*) FROM conversations')->fetchColumn()
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>管理后台</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="dashboard-stats">
        <div class="stat-card">
            <h3>注册用户</h3>
            <p><?= $stats['total_users'] ?></p>
        </div>
        <div class="stat-card">
            <h3>可用API</h3>
            <p><?= $stats['active_apis'] ?></p>
        </div>
        <div class="stat-card">
            <h3>对话总数</h3>
            <p><?= $stats['total_conversations'] ?></p>
        </div>
    </div>
</body>
</html>