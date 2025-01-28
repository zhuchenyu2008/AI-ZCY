<?php require '../includes/auth.php'; ?>
<!-- HTML结构 -->
<div id="chat-container">
    <?php foreach($conversations as $conv): ?>
    <div class="message">
        <div class="user-msg"><?= htmlspecialchars($conv['input']) ?></div>
        <div class="ai-msg">
            <?= nl2br(htmlspecialchars($conv['output'])) ?>
            <button onclick="copyText(this)">复制</button>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<form id="chat-form" enctype="multipart/form-data">
    <input type="text" name="message" required>
    <input type="file" name="image" accept="image/*">
    <select name="model">
        <option value="chatgpt-gpt4">GPT-4</option>
        <!-- 其他模型选项 -->
    </select>
    <button type="submit">发送</button>
</form>

<script>
function copyText(btn) {
    const text = btn.previousSibling.textContent;
    navigator.clipboard.writeText(text);
}
</script>