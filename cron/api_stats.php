<?php
/**
 * API使用统计聚合任务
 * 建议每天执行一次：0 0 * * * php /path/to/cron/api_stats.php
 */

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

// 日志文件配置
define('CRON_LOG', ROOT_PATH . '/cron/cron.log');

try {
    // 初始化数据库连接
    Database::init();

    // 统计前一天的API使用数据
    $start_date = date('Y-m-d 00:00:00', strtotime('-1 day'));
    $end_date = date('Y-m-d 23:59:59', strtotime('-1 day'));

    // 执行统计聚合
    aggregate_api_stats($start_date, $end_date);

    // 清理过期日志（保留30天）
    cleanup_old_logs(30);

    log_message("定时任务执行成功 - " . date('Y-m-d H:i:s'));
} catch (Exception $e) {
    log_message("错误: " . $e->getMessage(), true);
    exit(1);
}

/**
 * 聚合API使用统计
 */
function aggregate_api_stats($start, $end) {
    // 按提供商和模型分组统计
    $sql = "
        INSERT INTO api_stats (stat_date, provider, model_name, total_tokens, total_cost, request_count)
        SELECT 
            DATE(:start_date) AS stat_date,
            c.provider,
            c.model_name,
            SUM(l.tokens_used) AS total_tokens,
            SUM(l.cost) AS total_cost,
            COUNT(*) AS request_count
        FROM api_logs l
        JOIN api_configs c ON l.api_config_id = c.id
        WHERE l.timestamp BETWEEN :start_date AND :end_date
        GROUP BY c.provider, c.model_name
        ON DUPLICATE KEY UPDATE
            total_tokens = VALUES(total_tokens),
            total_cost = VALUES(total_cost),
            request_count = VALUES(request_count)
    ";

    Database::prepare($sql)->execute([
        ':start_date' => $start,
        ':end_date' => $end
    ]);
}

/**
 * 清理过期日志
 */
function cleanup_old_logs($keep_days = 30) {
    $delete_before = date('Y-m-d', strtotime("-$keep_days days"));
    $sql = "DELETE FROM api_logs WHERE timestamp < :delete_before";
    Database::prepare($sql)->execute([':delete_before' => $delete_before]);
}

/**
 * 记录日志
 */
function log_message($message, $is_error = false) {
    $prefix = $is_error ? "[ERROR] " : "[INFO] ";
    file_put_contents(CRON_LOG, $prefix . $message . PHP_EOL, FILE_APPEND);
}