-- 创建数据库
CREATE DATABASE IF NOT EXISTS `chatgpt_site` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE `chatgpt_site`;

-- 用户表
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0-普通用户 1-管理员',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 对话记录表（含图像支持）
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `content` TEXT NOT NULL COMMENT '文本内容或图片文件名',
  `is_image` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0-文本 1-图片',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- API密钥表（支持多服务商）
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `service` ENUM('chatgpt', 'deepseek') NOT NULL,
  `api_key` VARCHAR(255) NOT NULL,
  `usage_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `last_used` DATETIME DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `service_index` (`service`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 可选：初始化管理员账户（密码：admin123）
INSERT INTO `users` (`username`, `password_hash`, `is_admin`) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);