<?php
require '../includes/functions.php';
if (!isset($_SESSION['is_admin'])) die("无权限！");

// 获取所有用户
$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>注册时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= $user['id'] ?></td>
        <td><?= $user['username'] ?></td>
        <td><?= $user['created_at'] ?></td>
        <td><button onclick="deleteUser(<?= $user['id'] ?>)">删除</button></td>
    </tr>
    <?php endforeach; ?>
</table>
<script>
function deleteUser(userId) {
    if (confirm('确定删除用户？')) {
        fetch(`user_action.php?action=delete&id=${userId}`)
            .then(response => location.reload());
    }
}
</script>