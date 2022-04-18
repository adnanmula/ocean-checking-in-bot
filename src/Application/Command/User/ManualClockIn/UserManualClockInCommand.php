<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\ManualClockIn;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use Assert\Assert;
use PcComponentes\Ddd\Application\Command;

final class UserManualClockInCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';

    public const NAME = 'user_manual_clock_in';
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

        $this->reference = self::PAYLOAD_REFERENCE;
    }

    public function reference(): string
    {
        return $this->reference;
    }
}
