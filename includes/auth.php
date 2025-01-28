<?php
require_once 'config.php';
require_once 'database.php';

// 用户登录
function login($username, $password) {
    $user = Database::prepare("SELECT * FROM users WHERE username = ?")
           ->execute([$username])->fetch();
    
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = $user['is_admin'];
        return true;
    }
    return false;
}

// 用户注册
function register($username, $password, $email) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        Database::prepare("INSERT INTO users (username, password_hash, email) VALUES (?, ?, ?)")
               ->execute([$username, $hash, $email]);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

// 验证登录状态
function verify_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../pages/login.php");
        exit;
    }
}

// 验证管理员权限（已包含在之前的回答）
function verify_admin() {
    verify_login();
    if (!$_SESSION['is_admin']) {
        header("HTTP/1.1 403 Forbidden");
        exit("无管理权限");
    }
}

// 登出功能
function logout() {
    session_unset();
    session_destroy();
}
?>