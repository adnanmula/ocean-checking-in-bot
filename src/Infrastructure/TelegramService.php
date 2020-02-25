<?php declare(strict_types=1);

namespace DemigrantSoft\Infrastructure;

class TelegramService
{
    protected \Telegram $client;
    protected string $chatId;

    public function __construct(string $token, string $chatId)
    {
        $this->client = new \Telegram($token);
        $this->chatId = $chatId;
    }
}
