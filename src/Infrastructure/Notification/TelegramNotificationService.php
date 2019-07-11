<?php declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Domain\Notification\NotificationService;

final class TelegramNotificationService implements NotificationService
{
    private $client;
    private $chatId;

    public function __construct(string $token, string $chatId)
    {
        $this->client = new \Telegram($token);
        $this->chatId = $chatId;
    }

    public function notify(string $msg): void
    {
        $this->client->sendMessage([
            'chat_id' => $this->chatId,
            'parse_mode' => 'markdown',
            'text' => $msg,
        ]);
    }
}
