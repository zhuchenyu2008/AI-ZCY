<?php
session_start();
require_once 'config.php';

// 检查登录状态
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// 管理员检查
function isAdmin($user_id) {
    $user = db_query("SELECT is_admin FROM users WHERE id = ?", [$user_id]);
    return $user && $user['is_admin'] == 1;
}

// 登录验证
function login($username, $password) {
    $user = db_query("SELECT * FROM users WHERE username = ?", [$username]);
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        return true;
    }
    return false;
}

// 用户注册
function register($username, $password) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    return db_exec(
        "INSERT INTO users (username, password) VALUES (?, ?)",
        [$username, $hashed]
    );
}

// 管理员权限验证
function checkAdmin() {
    if (!isset($_SESSION['user_id']) || !isAdmin($_SESSION['user_id'])) {
        header('Location: /login.php');
        exit();
    }
}
?>