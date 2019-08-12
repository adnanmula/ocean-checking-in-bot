<?php declare(strict_types=1);

namespace App\Infrastructure\Notification;

use App\Domain\Notification\NotificationService;
use App\Infrastructure\TelegramService;

final class TelegramNotificationService extends TelegramService implements NotificationService
{
    public function notify(string $msg): void
    {
        $this->client->sendMessage([
            'chat_id' => $this->chatId,
            'parse_mode' => 'markdown',
            'text' => $msg,
        ]);
    }
}
