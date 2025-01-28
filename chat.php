<?php
require_once 'includes/auth.php';
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

require_once 'includes/api_handler.php';
$api = new APIHandler();

// 处理消息提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    $isImage = isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK;
    
    if ($isImage) {
        // 处理图片上传
        $targetDir = "uploads/";
        $fileName = uniqid().'.'.pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['image']['tmp_name'], $targetDir.$fileName);
        $message = "图片: ".$targetDir.$fileName;
    }

    // 保存用户消息
    db_exec(
        "INSERT INTO conversations (user_id, content, is_image) VALUES (?, ?, ?)",
        [$_SESSION['user_id'], $message, $isImage]
    );

    // 获取AI响应
    $response = $api->processRequest($message, $isImage);
    
    // 保存AI响应
    db_exec(
        "INSERT INTO conversations (user_id, content, is_ai) VALUES (?, ?, ?)",
        [$_SESSION['user_id'], $response, 1]
    );
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AI Chat</title>
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
    <div class="chat-container">
        <div class="chat-history" id="chatHistory">
            <?php
            $messages = db_query(
                "SELECT * FROM conversations WHERE user_id = ? ORDER BY created_at ASC",
                [$_SESSION['user_id']],
                true
            );
            foreach ($messages as $msg): ?>
                <div class="message <?= $msg['is_ai'] ? 'ai' : 'user' ?>">
                    <?php if ($msg['is_image']): ?>
                        <img src="<?= $msg['content'] ?>" class="chat-image">
                    <?php else: ?>
                        <div class="message-content"><?= htmlspecialchars($msg['content']) ?></div>
                    <?php endif; ?>
                    <button class="copy-btn">Copy</button>
                </div>
            <?php endforeach; ?>
        </div>
        <form method="post" enctype="multipart/form-data" class="input-area">
            <input type="file" id="imageInput" name="image" accept="image/*">
            <textarea id="messageInput" name="message" required></textarea>
            <button type="submit">Send</button>
        </form>
    </div>
    <script src="assets/js/chat.js"></script>
</body>
</html>