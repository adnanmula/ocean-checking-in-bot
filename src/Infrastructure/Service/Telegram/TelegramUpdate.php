<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\Telegram;

final class TelegramUpdate
{
    private int $chatId;
    private string $username;
    private string $text;

    public function __construct(int $chatId, string $username, string $text)
    {
        $this->chatId = $chatId;
        $this->username = $username;
        $this->text = $text;
    }

    public function chatId(): int
    {
        return $this->chatId;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function text(): string
    {
        return $this->text;
    }

    public function isCommand(): bool
    {
        return \str_starts_with($this->text, '/');
    }

    public function command(): ?string
    {
        if (false === $this->isCommand()) {
            return null;
        }

        $parts = \explode(' ', $this->text);

        return \array_shift($parts);
    }

    public function commandArguments(): ?array
    {
        if (false === $this->isCommand()) {
            return null;
        }

        $arguments = \explode(' ', $this->text);

        \array_shift($arguments);

        return $arguments;
    }
}
