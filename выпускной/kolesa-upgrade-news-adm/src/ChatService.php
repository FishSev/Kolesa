<?php

namespace App;

use App\Config;

class ChatService
{
    static $config;
    public $chat;

    public function __construct(ChatClient $chat)
    {
        $this->chat = $chat;
        self::$config = Config::load();
    }

    function checkHealth(): bool
    {
        $serviceResponse = $this->chat::$client->request('GET', "/health", ['proxy' => self::$config["ChatService"]["proxy"]]);
        $result = json_decode($serviceResponse->getBody()->getContents(), associative: true);
        if ($result["status"] === "ok") {
            return true;
        } else {
            throw new \Exception("Неправильный статус сервера бота");
        }
    }

    function sendMessage(array $messageData)
    {
        $data = [
            'proxy' => self::$config["ChatService"]["proxy"],
            'body' => json_encode($messageData),
        ];
        
        $serviceResponse = $this->chat::$client->request('POST', "/messages/sendToAll", $data);
        $result = json_decode($serviceResponse->getBody()->getContents(), associative: true);
        if ($result["status"] != "ok") {
            throw new \Exception($result["error"]);
        }
    }
}
