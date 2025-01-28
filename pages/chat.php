<?php
require_once '../includes/auth.php';
require_once '../includes/config.php';
verify_login();

// 处理消息发送
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    try {
        $message = sanitize_input($_POST['message'] ?? '');
        $image_path = null;

        // 处理图片上传
        if (!empty($_FILES['image'])) {
            $image_path = handle_upload($_FILES['image']);
        }

        // 调用AI接口
        $model_type = $_SESSION['selected_model'] ?? 'chatgpt'; // 默认使用ChatGPT
        $response = APIHandler::sendRequest($model_type, $message, $image_path);

        // 保存对话记录
        $stmt = Database::prepare("
            INSERT INTO conversations 
            (user_id, input_text, input_image, output_text, model_used)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $_SESSION['user_id'],
            $message,
            $image_path,
            $response,
            $model_type
        ]);

        echo json_encode(['success' => true, 'response' => $response]);
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

// 获取历史消息
if (isset($_GET['action']) && $_GET['action'] === 'get_history') {
    $stmt = Database::prepare("
        SELECT input_text, input_image, output_text, created_at 
        FROM conversations 
        WHERE user_id = ?
        ORDER BY created_at DESC
        LIMIT 50
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $history = $stmt->fetchAll();
    
    header('Content-Type: application/json');
    echo json_encode($history);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>AI对话</title>
    <link rel="stylesheet" href="../static/css/style.css">
</head>
<body>
    <?php include '../includes/user_header.php'; ?>
    
    <div class="chat-container">
        <div class="chat-history" id="chat-history">
            <!-- 通过JavaScript加载历史消息 -->
        </div>
        
        <div class="input-area">
            <form id="chat-form" enctype="multipart/form-data">
                <textarea id="chat-input" name="message" placeholder="输入消息..."></textarea>
                
                <div class="image-upload">
                    <input type="file" id="image-input" name="image" accept="image/*">
                    <label for="image-input" class="btn-upload">上传图片</label>
                    <img id="image-preview" style="display:none;">
                </div>

                <div class="model-selector">
                    <select id="model-select">
                        <option value="chatgpt">ChatGPT</option>
                        <option value="deepseek">DeepSeek</option>
                    </select>
                </div>

                <button type="button" id="send-button" class="btn-send">发送</button>
            </form>
        </div>
    </div>
    
    <script src="../static/js/chat.js"></script>
    <script src="../static/js/clipboard.js"></script>
</body>
</html>