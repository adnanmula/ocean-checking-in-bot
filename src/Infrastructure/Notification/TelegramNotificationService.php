<?php declare(strict_types=1);

namespace DemigrantSoft\Infrastructure\Notification;

use DemigrantSoft\Domain\Notification\NotificationService;
use DemigrantSoft\Infrastructure\TelegramService;

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
