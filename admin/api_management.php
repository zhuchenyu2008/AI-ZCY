<?php 
require '../includes/auth.php';
if (!isAdmin()) die('Access denied');

// 处理API添加
if ($_POST) {
    $service = $_POST['service'];
    $key = $_POST['api_key'];
    $models = implode(',', $_POST['models']);
    
    $stmt = $pdo->prepare("INSERT INTO api_keys (service, api_key, models) VALUES (?, ?, ?)");
    $stmt->execute([$service, $key, $models]);
}
?>

<form method="post">
    <select name="service">
        <option value="chatgpt">ChatGPT</option>
        <option value="deepseek">DeepSeek</option>
    </select>
    <input type="text" name="api_key" required>
    <div class="model-select">
        <!-- 动态生成模型选项 -->
    </div>
    <button type="submit">添加API</button>
</form>