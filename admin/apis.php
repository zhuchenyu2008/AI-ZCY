<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_admin();

// 处理表单提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_api'])) {
        $provider = $_POST['provider'];
        $model = $_POST['model'];
        $key = $_POST['api_key'];
        $endpoint = $_POST['endpoint'];
        
        Database::prepare("INSERT INTO api_configs (provider, model_name, api_key, endpoint) 
                          VALUES (?, ?, ?, ?)")
               ->execute([$provider, $model, $key, $endpoint]);
    } elseif (isset($_POST['toggle_api'])) {
        $api_id = $_POST['api_id'];
        Database::prepare("UPDATE api_configs SET is_active = NOT is_active WHERE id = ?")
               ->execute([$api_id]);
    } elseif (isset($_POST['delete_api'])) {
        $api_id = $_POST['api_id'];
        Database::prepare("DELETE FROM api_configs WHERE id = ?")->execute([$api_id]);
    }
    header("Location: apis.php");
    exit;
}

// 获取所有API配置
$apis = Database::query("SELECT * FROM api_configs ORDER BY provider, model_name")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>API管理</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/admin_header.php'; ?>
    
    <div class="api-management">
        <h2>添加新API</h2>
        <form method="POST" class="api-form">
            <select name="provider" required>
                <option value="chatgpt">ChatGPT</option>
                <option value="deepseek">DeepSeek</option>
            </select>
            <input type="text" name="model" placeholder="模型名称" required>
            <input type="text" name="api_key" placeholder="API密钥" required>
            <input type="url" name="endpoint" placeholder="API端点" required>
            <button type="submit" name="add_api">添加API</button>
        </form>

        <h2>现有API列表</h2>
        <table>
            <tr>
                <th>提供商</th>
                <th>模型</th>
                <th>端点</th>
                <th>状态</th>
                <th>操作</th>
            </tr>
            <?php foreach ($apis as $api): ?>
            <tr>
                <td><?= strtoupper($api['provider']) ?></td>
                <td><?= $api['model_name'] ?></td>
                <td class="truncate"><?= $api['endpoint'] ?></td>
                <td><?= $api['is_active'] ? '启用' : '禁用' ?></td>
                <td>
                    <form method="POST" style="display:inline">
                        <input type="hidden" name="api_id" value="<?= $api['id'] ?>">
                        <button type="submit" name="toggle_api" class="btn-toggle">
                            <?= $api['is_active'] ? '禁用' : '启用' ?>
                        </button>
                    </form>
                    <form method="POST" onsubmit="return confirm('确定删除此API配置？')" style="display:inline">
                        <input type="hidden" name="api_id" value="<?= $api['id'] ?>">
                        <button type="submit" name="delete_api" class="btn-danger">删除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>