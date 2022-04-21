<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\Telegram;

final class TelegramClient
{
    private \Telegram $client;

    public function __construct(string $botToken)
    {
        $this->client = new \Telegram($botToken);
    }

    public function getUpdates(): \Traversable
    {
        $this->client->getUpdates();

        for ($i = 0; $i < $this->client->UpdateCount(); $i++) {
            $this->client->serveUpdate($i);

            yield new TelegramUpdate(
                $this->client->ChatID(),
                $this->client->Username(),
                $this->client->Text()
            );
        }
    }

    public function sendMessage(int $chatId, string $text)
    {
        $this->client->sendMessage(['chat_id' => $chatId, 'text' => $text]);
    }
}
