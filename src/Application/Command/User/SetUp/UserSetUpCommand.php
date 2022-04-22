<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use Assert\Assert;
use PcComponentes\Ddd\Application\Command;

final class UserSetUpCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_KEY = 'key';
    public const PAYLOAD_VALUE = 'value';

    public const NAME = 'set_up';
    public const VERSION = '1';

    private string $reference;
    private string $key;
    private string $value;

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

    public function key(): string
    {
        return $this->key;
    }

    public function value(): string
    {
        return $this->value;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PAYLOAD_REFERENCE)
            ->keyExists(self::PAYLOAD_KEY)
            ->keyExists(self::PAYLOAD_VALUE)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->that($payload[self::PAYLOAD_KEY], self::PAYLOAD_KEY)->string()->notBlank()
            ->that($payload[self::PAYLOAD_VALUE], self::PAYLOAD_VALUE)->string()->notBlank()
            ->verifyNow();

        $this->reference = $payload[$payload[self::PAYLOAD_REFERENCE]];
        $this->key = $payload[$payload[self::PAYLOAD_KEY]];
        $this->value = $payload[$payload[self::PAYLOAD_VALUE]];
    }
}
