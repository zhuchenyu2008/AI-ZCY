<?php
require_once 'config.php';
require_once 'vendor/autoload.php'; // 假设已安装官方SDK

class APIHandler {
    public function processRequest($input, $isImage = false) {
        $activeAPI = $this->getActiveAPI();
        
        switch($activeAPI['api_type']) {
            case 'chatgpt':
                return $this->handleChatGPT($input, $activeAPI, $isImage);
            case 'deepseek':
                return $this->handleDeepSeek($input, $activeAPI);
            default:
                throw new Exception("Unsupported API type");
        }
    }

    private function getActiveAPI() {
        return db_query("SELECT * FROM api_configs WHERE status = 1 LIMIT 1");
    }

    private function handleChatGPT($input, $config, $isImage) {
        $client = OpenAI::client($config['api_key']);
        
        if($isImage) {
            $response = $client->images()->create([
                'prompt' => $input,
                'n' => 1,
                'size' => '512x512'
            ]);
            return $response->data[0]->url;
        } else {
            $response = $client->chat()->create([
                'model' => 'gpt-4',
                'messages' => [['role' => 'user', 'content' => $input]]
            ]);
            return $response->choices[0]->message->content;
        }
    }

    private function handleDeepSeek($input, $config) {
        $client = new DeepSeek\Client($config['api_key']);
        $response = $client->chat()->create([
            'model' => 'deepseek-chat',
            'messages' => [['role' => 'user', 'content' => $input]]
        ]);
        return $response->choices[0]->message->content;
    }

    public function logUsage($user_id, $api_id, $tokens) {
        db_exec(
            "INSERT INTO api_logs (user_id, api_id, tokens_used) VALUES (?, ?, ?)",
            [$user_id, $api_id, $tokens]
        );
    }
}
?>