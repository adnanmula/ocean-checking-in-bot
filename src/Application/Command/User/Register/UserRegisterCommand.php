<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\Register;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use Assert\Assert;
use PcComponentes\Ddd\Application\Command;

final class UserRegisterCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_USERNAME = 'username';

    public const NAME = 'user_register';
    public const VERSION = '1';

    private string $reference;
    private string $username;

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

    public function username(): string
    {
        return $this->username;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PAYLOAD_REFERENCE)
            ->keyExists(self::PAYLOAD_USERNAME)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->that($payload[self::PAYLOAD_USERNAME], self::PAYLOAD_USERNAME)->string()->notBlank()
            ->verifyNow();

        $this->reference = $payload[self::PAYLOAD_REFERENCE];
        $this->username = $payload[self::PAYLOAD_USERNAME];
    }
}
