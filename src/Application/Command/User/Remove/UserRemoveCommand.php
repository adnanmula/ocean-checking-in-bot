<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\Remove;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use Assert\Assert;
use PcComponentes\Ddd\Application\Command;

final class UserRemoveCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';

    public const NAME = 'remove';
    public const VERSION = '1';

    private string $reference;

    public static function messageName(): string
    {
        return 'adnanmula.clock-in-bot.'
            . self::messageVersion() . '.'
            . self::messageType() . '.'
            . User::modelName() . '.'
            . self::NAME;
    }

    public static function messageVersion(): string
    {
        return self::VERSION;
    }

    public function reference(): string
    {
        return $this->reference;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PAYLOAD_REFERENCE)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->verifyNow();

        $this->reference = $payload[self::PAYLOAD_REFERENCE];
    }
}
