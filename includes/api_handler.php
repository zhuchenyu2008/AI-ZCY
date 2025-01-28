class AIHandler {
    public static function handleRequest($input, $modelType, $image = null) {
        $apiKey = self::getApiKey($modelType);
        
        if (strpos($modelType, 'chatgpt') === 0) {
            return self::handleChatGPT($input, $modelType, $apiKey, $image);
        } elseif (strpos($modelType, 'deepseek') === 0) {
            return self::handleDeepSeek($input, $modelType, $apiKey);
        }
    }

    private static function handleChatGPT($input, $model, $apiKey, $image) {
        $messages = [['role' => 'user', 'content' => $input]];
        
        if ($image) {
            $messages[0]['content'] = [
                ['type' => 'text', 'text' => $input],
                ['type' => 'image_url', 'image_url' => $image]
            ];
        }

        $data = [
            'model' => $model,
            'messages' => $messages,
            'max_tokens' => 1000
        ];

        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);
        // 其他curl设置...
        $response = curl_exec($ch);
        return json_decode($response, true);
    }
}