<?php
session_start();
define('BASE_URL', 'http://your-domain.com/'); // 替换为你的域名
define('UPLOAD_DIR', __DIR__ . '/../uploads/'); // 文件上传目录
ini_set('upload_max_filesize', '10M'); // 允许上传最大10MB文件