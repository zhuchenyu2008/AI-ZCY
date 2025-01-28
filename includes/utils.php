<?php
// 文件上传处理
function handle_upload($file) {
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file['type'], $allowed_types)) {
        throw new Exception("仅支持JPEG/PNG/GIF格式");
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        throw new Exception("文件大小不能超过5MB");
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid() . '.' . $ext;
    $target_path = UPLOAD_PATH . $filename;

    if (!move_uploaded_file($file['tmp_name'], $target_path)) {
        throw new Exception("文件上传失败");
    }

    return '/static/uploads/' . $filename;
}

// Base64编码文件
function file_to_base64($filepath) {
    $data = file_get_contents(ROOT_PATH . $filepath);
    return 'data:' . mime_content_type(ROOT_PATH . $filepath) . ';base64,' . base64_encode($data);
}

// 安全过滤输入
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// 生成分页链接
function generate_pagination($total_pages, $current_page, $url) {
    $output = '<div class="pagination">';
    for ($i = 1; $i <= $total_pages; $i++) {
        $active = $i == $current_page ? 'active' : '';
        $output .= "<a href='{$url}?page=$i' class='$active'>$i</a>";
    }
    $output .= '</div>';
    return $output;
}
?>