<?php
// 禁用错误报告（上线时启用）
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 基础路径配置
define('ROOT_PATH', realpath(dirname(__FILE__) . '/..'));
define('UPLOAD_PATH', ROOT_PATH . '/static/uploads/');

// 数据库配置
define('DB_HOST', 'localhost');
define('DB_NAME', 'ai_chat');
define('DB_USER', 'root');
define('DB_PASS', 'your_password');

// 会话配置
session_start();

// 时区设置
date_default_timezone_set('Asia/Shanghai');

// 自动加载类
spl_autoload_register(function ($class) {
    include __DIR__ . '/' . $class . '.php';
});

// 初始化数据库连接
require_once 'database.php';
Database::init();
?>