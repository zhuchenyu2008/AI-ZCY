// 初始化复制功能
document.addEventListener('DOMContentLoaded', () => {
    document.body.addEventListener('click', (e) => {
        if (e.target.classList.contains('copy-btn')) {
            const text = e.target.dataset.text;
            copyToClipboard(text);
            showCopyFeedback(e.target);
        }
    });

    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
        } catch (err) {
            // 降级方案
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        }
    }

    function showCopyFeedback(button) {
        const originalText = button.textContent;
        button.textContent = '已复制！';
        button.style.backgroundColor = '#27ae60';
        setTimeout(() => {
            button.textContent = originalText;
            button.style.backgroundColor = '';
        }, 2000);
    }
});