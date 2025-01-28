document.addEventListener('DOMContentLoaded', () => {
    const chatInput = document.getElementById('chat-input');
    const sendButton = document.getElementById('send-button');
    const imageInput = document.getElementById('image-input');
    const imagePreview = document.getElementById('image-preview');
    const chatHistory = document.getElementById('chat-history');
    
    // 初始化消息历史
    loadChatHistory();

    // 发送消息逻辑
    sendButton.addEventListener('click', async () => {
        const message = chatInput.value.trim();
        const imageFile = imageInput.files[0];
        
        if (!message && !imageFile) return;

        // 禁用按钮
        sendButton.disabled = true;
        sendButton.textContent = '发送中...';

        try {
            const formData = new FormData();
            formData.append('message', message);
            if (imageFile) formData.append('image', imageFile);

            const response = await fetch('/pages/chat.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            
            if (result.success) {
                appendMessage(message, result.response, imageFile);
                chatInput.value = '';
                imageInput.value = '';
                imagePreview.style.display = 'none';
            } else {
                alert('发送失败: ' + result.error);
            }
        } catch (error) {
            alert('网络错误: ' + error.message);
        } finally {
            sendButton.disabled = false;
            sendButton.textContent = '发送';
        }
    });

    // 图片预览
    imageInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            imagePreview.src = URL.createObjectURL(file);
            imagePreview.style.display = 'block';
        }
    });

    // 消息历史操作
    function appendMessage(input, output, imageFile) {
        const timestamp = new Date().toLocaleString();
        const messageHtml = `
            <div class="chat-message">
                <div class="message-user">您 (${timestamp}):</div>
                ${input ? `<div class="user-input">${input}</div>` : ''}
                ${imageFile ? `<img src="${URL.createObjectURL(imageFile)}" class="message-image">` : ''}
                <div class="message-ai">AI回复:</div>
                <div class="ai-output">${output}</div>
                <button class="copy-btn" data-text="${output}">复制</button>
            </div>
        `;
        chatHistory.insertAdjacentHTML('beforeend', messageHtml);
        chatHistory.scrollTop = chatHistory.scrollHeight;
    }

    async function loadChatHistory() {
        const response = await fetch('/pages/chat.php?action=get_history');
        const messages = await response.json();
        chatHistory.innerHTML = messages.map(msg => `
            <div class="chat-message">
                <div class="message-user">您 (${msg.created_at}):</div>
                ${msg.input_text ? `<div class="user-input">${msg.input_text}</div>` : ''}
                ${msg.input_image ? `<img src="${msg.input_image}" class="message-image">` : ''}
                <div class="message-ai">AI回复:</div>
                <div class="ai-output">${msg.output_text}</div>
                <button class="copy-btn" data-text="${msg.output_text}">复制</button>
            </div>
        `).join('');
    }
});