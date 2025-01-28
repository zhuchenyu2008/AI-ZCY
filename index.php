<?php
// 网站入口文件
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/auth.php';

// 自动路由控制
if (isset($_SESSION['user_id'])) {
    // 已登录用户重定向到聊天界面
    header("Location: pages/chat.php");
} else {
    // 未登录用户重定向到登录页
    header("Location: pages/login.php");
}
exit;