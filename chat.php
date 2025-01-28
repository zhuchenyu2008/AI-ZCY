<?php
require 'includes/config.php';
require 'includes/functions.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// 处理消息发送
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $isImage = 0;
    
    // 处理图片上传
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = UPLOAD_DIR;
        $filename = uniqid() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);
        $message = $filename; // 保存文件名到数据库
        $isImage = 1;
    }
    
    // 保存用户消息
    $stmt = $pdo->prepare("INSERT INTO conversations (user_id, content, is_image) VALUES (?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $message, $isImage]);
    
    // 调用AI API（示例）
    $apiKey = $pdo->query("SELECT api_key FROM api_keys WHERE service='chatgpt' LIMIT 1")->fetchColumn();
    $aiResponse = callChatGPT($apiKey, $message);
    
    // 保存AI回复
    $stmt->execute([$_SESSION['user_id'], $aiResponse, 0]);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>聊天界面</title>
    <style>
        .image-msg img { max-width: 200px; }
    </style>
</head>
<body>
    <div id="chat-history">
        <?php
        $stmt = $pdo->prepare("SELECT * FROM conversations WHERE user_id = ? ORDER BY created_at");
        $stmt->execute([$_SESSION['user_id']]);
        while ($msg = $stmt->fetch()) {
            if ($msg['is_image']) {
                echo '<div class="image-msg"><img src="uploads/'.$msg['content'].'"></div>';
            } else {
                echo '<div>'.$msg['content'].'</div>';
            }
        }
        ?>
    </div>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="message" placeholder="输入文字...">
        <input type="file" name="image" accept="image/*">
        <button type="submit">发送</button>
    </form>
    <button onclick="copyChat()">一键复制对话</button>
    <script>
        function copyChat() {
            const chatText = document.getElementById('chat-history').innerText;
            navigator.clipboard.writeText(chatText).then(() => {
                alert('已复制到剪贴板！');
            });
        }
    </script>
</body>
</html>