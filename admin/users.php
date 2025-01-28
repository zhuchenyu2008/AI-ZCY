<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_admin();

// 分页设置
$per_page = 20;
$page = $_GET['page'] ?? 1;
$offset = ($page - 1) * $per_page;

// 获取用户数据
$stmt = Database::prepare("SELECT * FROM users ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$users = $stmt->fetchAll();

// 获取总用户数
$total_users = Database::query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_pages = ceil($total_users / $per_page);

// 处理删除请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    Database::prepare("DELETE FROM users WHERE id = ?")->execute([$user_id]);
    header("Location: users.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>用户管理</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="user-list">
        <table>
            <tr>
                <th>ID</th>
                <th>用户名</th>
                <th>注册时间</th>
                <th>管理员</th>
                <th>操作</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= htmlspecialchars($user['username']) ?></td>
                <td><?= $user['created_at'] ?></td>
                <td><?= $user['is_admin'] ? '是' : '否' ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('确定删除该用户？')">
                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                        <button type="submit" name="delete_user" class="btn-danger">删除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>