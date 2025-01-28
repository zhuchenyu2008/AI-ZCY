// 一键复制功能
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.copy-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const content = e.target.closest('.message').querySelector('.message-content');
            navigator.clipboard.writeText(content.innerText);
            showToast('Copied!');
        });
    });
});

// 图片预览
document.getElementById('imageInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (event) => {
            const preview = document.createElement('img');
            preview.src = event.target.result;
            preview.className = 'preview-image';
            document.getElementById('chatHistory').appendChild(preview);
        };
        reader.readAsDataURL(file);
    }
});

// 消息自动滚动
const chatHistory = document.getElementById('chatHistory');
chatHistory.scrollTop = chatHistory.scrollHeight;

function showToast(message) {
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}