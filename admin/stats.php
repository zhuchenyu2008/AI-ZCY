<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_admin();

// 获取统计周期
$range = $_GET['range'] ?? 'week';
$start_date = match($range) {
    'day' => date('Y-m-d 00:00:00'),
    'week' => date('Y-m-d 00:00:00', strtotime('-7 days')),
    'month' => date('Y-m-01 00:00:00'),
    default => '2020-01-01'
};

// 获取统计信息
$stats = Database::prepare("
    SELECT 
        c.provider,
        SUM(l.tokens_used) as total_tokens,
        SUM(l.cost) as total_cost,
        COUNT(*) as requests
    FROM api_logs l
    JOIN api_configs c ON l.api_config_id = c.id
    WHERE l.timestamp >= ?
    GROUP BY c.provider
")->execute([$start_date])->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>使用统计</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/admin_header.php']; ?>
    
    <div class="stats-controls">
        <form method="GET">
            <select name="range" onchange="this.form.submit()">
                <option value="day" <?= $range === 'day' ? 'selected' : '' ?>>今日</option>
                <option value="week" <?= $range === 'week' ? 'selected' : '' ?>>本周</option>
                <option value="month" <?= $range === 'month' ? 'selected' : '' ?>>本月</option>
            </select>
        </form>
    </div>

    <div class="stats-grid">
        <?php foreach ($stats as $stat): ?>
        <div class="stat-card">
            <h3><?= strtoupper($stat['provider']) ?></h3>
            <p>请求次数：<?= $stat['requests'] ?></p>
            <p>Token使用：<?= $stat['total_tokens'] ?></p>
            <p>总成本：$<?= number_format($stat['total_cost'], 4) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>