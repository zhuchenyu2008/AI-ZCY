<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_admin();

// 分页和搜索处理
$per_page = 15;
$page = $_GET['page'] ?? 1;
$search = $_GET['search'] ?? '';
$offset = ($page - 1) * $per_page;

// 构建查询
$sql = "SELECT c.*, u.username 
        FROM conversations c 
        JOIN users u ON c.user_id = u.id
        WHERE u.username LIKE :search 
        ORDER BY c.created_at DESC 
        LIMIT :limit OFFSET :offset";

$stmt = Database::prepare($sql);
$stmt->bindValue(':search', "%$search%");
$stmt->bindValue(':limit', $per_page, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$conversations = $stmt->fetchAll();

// 获取总数
$total = Database::prepare("SELECT COUNT(*) 
                          FROM conversations c
                          JOIN users u ON c.user_id = u.id
                          WHERE u.username LIKE :search")
         ->execute([':search' => "%$search%"])->fetchColumn();
$total_pages = ceil($total / $per_page);
?>
<!DOCTYPE html>
<html>
<head>
    <title>对话监控</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="search-box">
        <form method="GET">
            <input type="text" name="search" placeholder="搜索用户名..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit">搜索</button>
        </form>
    </div>

    <div class="conversation-list">
        <table>
            <tr>
                <th>用户</th>
                <th>输入内容</th>
                <th>输出内容</th>
                <th>使用模型</th>
                <th>时间</th>
            </tr>
            <?php foreach ($conversations as $conv): ?>
            <tr>
                <td><?= htmlspecialchars($conv['username']) ?></td>
                <td class="truncate"><?= htmlspecialchars($conv['input_text']) ?></td>
                <td class="truncate"><?= htmlspecialchars($conv['output_text']) ?></td>
                <td><?= $conv['model_used'] ?></td>
                <td><?= date('m-d H:i', strtotime($conv['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>&search=<?= urlencode($search) ?>" 
                   class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</body>
</html>