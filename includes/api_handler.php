<?php
require_once 'config.php';
require_once 'utils.php';

class APIHandler {
    public static function sendRequest($model_type, $input_text, $input_image = null) {
        // 获取可用API配置
        $api = self::getAvailableAPI($model_type);
        
        // 构建请求数据
        $payload = [
            'model' => $api['model_name'],
            'messages' => [['role' => 'user', 'content' => $input_text]]
        ];

        // 处理图片输入
        if ($input_image) {
            if ($api['provider'] === 'chatgpt') {
                $payload['messages'][0]['image'] = file_to_base64($input_image);
            } elseif ($api['provider'] === 'deepseek') {
                $payload['attachments'] = [['type' => 'image', 'data' => file_to_base64($input_image)]];
            }
        }

        // 发送请求
        $ch = curl_init($api['endpoint']);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $api['api_key']
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // 处理响应
        if ($http_code === 200) {
            $data = json_decode($response, true);
            self::logUsage($api['id'], $_SESSION['user_id'], $data['usage']['total_tokens']);
            return $data['choices'][0]['message']['content'];
        } else {
            throw new Exception("API请求失败: HTTP $http_code - $response");
        }
    }

    private static function getAvailableAPI($model_type) {
        $stmt = Database::prepare("
            SELECT * FROM api_configs 
            WHERE provider = ? AND is_active = 1
            ORDER BY RAND()
            LIMIT 1
        ");
        $stmt->execute([$model_type]);
        return $stmt->fetch() ?? throw new Exception("无可用API配置");
    }

    private static function logUsage($api_id, $user_id, $tokens) {
        $cost = $tokens * 0.000002; // 示例计费逻辑
        Database::prepare("
            INSERT INTO api_logs (api_config_id, user_id, tokens_used, cost)
            VALUES (?, ?, ?, ?)
        ")->execute([$api_id, $user_id, $tokens, $cost]);
    }
}
?>