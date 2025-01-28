<?php
require '../includes/config.php';
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: ../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
<body>
    <h1>管理员面板</h1>
    <ul>
        <li><a href="users.php">用户管理</a></li>
        <li><a href="apis.php">API管理</a></li>
    </ul>
</body>
</html>