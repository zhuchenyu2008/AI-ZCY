<?php
require '../includes/functions.php';
if (!isset($_SESSION['is_admin'])) die("无权限！");

// 添加API
if ($_POST['action'] === 'add') {
    $service = $_POST['service'];
    $key = $_POST['api_key'];
    $pdo->prepare("INSERT INTO api_keys (service, api_key) VALUES (?, ?)")->execute([$service, $key]);
}

// 删除API
if ($_GET['action'] === 'delete') {
    $pdo->prepare("DELETE FROM api_keys WHERE id = ?")->execute([$_GET['id']]);
}

$apis = $pdo->query("SELECT * FROM api_keys")->fetchAll();
?>
<form method="POST">
    <input type="hidden" name="action" value="add">
    <select name="service">
        <option value="chatgpt">ChatGPT</option>
        <option value="deepseek">DeepSeek</option>
    </select>
    <input type="text" name="api_key" placeholder="API密钥" required>
    <button type="submit">添加API</button>
</form>

<table border="1">
    <?php foreach ($apis as $api): ?>
    <tr>
        <td><?= $api['service'] ?></td>
        <td><?= substr($api['api_key'], 0, 6) ?>******</td>
        <td><?= $api['usage_count'] ?></td>
        <td><a href="?action=delete&id=<?= $api['id'] ?>">删除</a></td>
    </tr>
    <?php endforeach; ?>
</table>