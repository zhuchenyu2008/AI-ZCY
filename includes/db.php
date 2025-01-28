<?php
require 'config.php';
$host = 'localhost';
$dbname = 'chatgpt_site';
$user = 'root';
$pass = 'your_db_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("数据库连接失败: " . $e->getMessage());
}