<?php declare(strict_types=1);

namespace App\Infrastructure;

class TelegramService
{
    protected $client;
    protected $chatId;

    public function __construct(string $token, string $chatId)
    {
        $this->client = new \Telegram($token);
        $this->chatId = $chatId;
    }
}
