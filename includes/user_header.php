<?php
// 用户公共头部
if (!isset($_SESSION)) session_start();
?>
<header class="user-header">
    <div class="logo">AI Chat</div>
    <nav class="user-nav">
        <a href="chat.php">对话</a>
        <a href="profile.php">个人中心</a>
        <?php if ($_SESSION['is_admin'] ?? 0): ?>
            <a href="../admin/">管理后台</a>
        <?php endif; ?>
        <a href="../includes/logout.php">退出</a>
    </nav>
</header>