<?php
require_once '../includes/auth.php';
checkAdmin();

// 获取所有用户
$users = db_query("SELECT * FROM users", [], true);
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Management</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="admin-container">
        <h1>User Management</h1>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Registered</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td>
                    <button onclick="location.href='user_detail.php?id=<?= $user['id'] ?>'">View Chats</button>
                    <?php if($user['is_admin'] == 0): ?>
                        <button onclick="deleteUser(<?= $user['id'] ?>)">Delete</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
    <script>
    function deleteUser(userId) {
        if(confirm('Are you sure?')) {
            fetch(`user_action.php?action=delete&id=${userId}`)
            .then(response => location.reload());
        }
    }
    </script>
</body>
</html>