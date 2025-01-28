-- File: ai_chat_system.sql
-- Description: AI对话系统数据库结构
-- Version: 1.0
-- Author: Your Name
-- Created: 2024-02-20

-- 设置数据库字符集
CREATE DATABASE IF NOT EXISTS `ai_chat`
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_unicode_ci;

USE `ai_chat`;

-- ----------------------------
-- Table structure for users
-- ----------------------------
CREATE TABLE `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL COMMENT '用户名',
  `password_hash` VARCHAR(255) NOT NULL COMMENT '密码哈希值',
  `email` VARCHAR(100) NOT NULL COMMENT '邮箱地址',
  `is_admin` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '是否管理员 (0-否 1-是)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户表';

-- ----------------------------
-- Table structure for conversations
-- ----------------------------
CREATE TABLE `conversations` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL COMMENT '用户ID',
  `input_text` TEXT NULL DEFAULT NULL COMMENT '用户输入文本',
  `input_image` VARCHAR(255) NULL DEFAULT NULL COMMENT '用户输入图片路径',
  `output_text` TEXT NULL DEFAULT NULL COMMENT 'AI输出文本',
  `output_image` VARCHAR(255) NULL DEFAULT NULL COMMENT 'AI生成图片路径',
  `model_used` VARCHAR(50) NOT NULL COMMENT '使用的AI模型',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `fk_conversations_users_idx` (`user_id` ASC),
  INDEX `idx_created_at` (`created_at` DESC),
  CONSTRAINT `fk_conversations_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='对话记录表';

-- ----------------------------
-- Table structure for api_configs
-- ----------------------------
CREATE TABLE `api_configs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `provider` ENUM('chatgpt', 'deepseek') NOT NULL COMMENT 'API提供商',
  `model_name` VARCHAR(100) NOT NULL COMMENT '模型名称',
  `api_key` VARCHAR(255) NOT NULL COMMENT 'API密钥',
  `endpoint` VARCHAR(255) NOT NULL COMMENT 'API端点地址',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1 COMMENT '是否启用 (0-禁用 1-启用)',
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`id`),
  INDEX `idx_provider` (`provider` ASC),
  INDEX `idx_active` (`is_active` ASC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API配置表';

-- ----------------------------
-- Table structure for api_logs
-- ----------------------------
CREATE TABLE `api_logs` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `api_config_id` INT(11) NOT NULL COMMENT 'API配置ID',
  `user_id` INT(11) NOT NULL COMMENT '用户ID',
  `tokens_used` INT(11) NOT NULL DEFAULT 0 COMMENT '使用的Token数量',
  `cost` DECIMAL(10,6) NOT NULL DEFAULT 0.000000 COMMENT '调用成本',
  `timestamp` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '记录时间',
  PRIMARY KEY (`id`),
  INDEX `fk_api_logs_api_configs_idx` (`api_config_id` ASC),
  INDEX `fk_api_logs_users_idx` (`user_id` ASC),
  INDEX `idx_timestamp` (`timestamp` DESC),
  CONSTRAINT `fk_api_logs_api_configs`
    FOREIGN KEY (`api_config_id`)
    REFERENCES `api_configs` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_api_logs_users`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='API调用日志表';

-- ----------------------------
-- 初始数据插入
-- ----------------------------

-- 默认管理员账户
-- 用户名: admin
-- 密码: admin123 (使用 password_hash() 生成)
INSERT INTO `users` 
  (`username`, `password_hash`, `email`, `is_admin`)
VALUES
  ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@example.com', 1);

-- 示例API配置 (需要替换为实际值)
INSERT INTO `api_configs`
  (`provider`, `model_name`, `api_key`, `endpoint`)
VALUES
  ('chatgpt', 'gpt-4', 'sk-your-openai-key', 'https://api.openai.com/v1/chat/completions'),
  ('deepseek', 'deepseek-chat', 'your-deepseek-key', 'https://api.deepseek.com/v1/chat/completions');