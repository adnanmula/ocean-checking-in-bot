<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\Communication;

use DemigrantSoft\ClockInBot\Service\Notification\NotificationService;

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
