<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInPlatform;
use Assert\Assert;
use PcComponentes\Ddd\Application\Command;

final class UserSetUpCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_PLATFORM = 'platform';
    public const PAYLOAD_PARAMETERS = 'parameters';

    public const NAME = 'set_up';
    public const VERSION = '1';

    private string $reference;
    private ClockInPlatform $platform;
    private array $parameters;

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

    public function platform(): ClockInPlatform
    {
        return $this->platform;
    }

    public function parameters(): array
    {
        return $this->parameters;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()
            ->keyExists(self::PAYLOAD_REFERENCE)
            ->keyExists(self::PAYLOAD_PLATFORM)
            ->keyExists(self::PAYLOAD_PARAMETERS)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->that($payload[self::PAYLOAD_PLATFORM], self::PAYLOAD_PLATFORM)->string()->inArray(ClockInPlatform::allowedValues())
            ->that($payload[self::PAYLOAD_PARAMETERS], self::PAYLOAD_PARAMETERS)->all()->string()
            ->verifyNow();

        $this->reference = $payload[self::PAYLOAD_REFERENCE];
        $this->platform = ClockInPlatform::from($payload[self::PAYLOAD_PLATFORM]);
        $this->parameters = $payload[self::PAYLOAD_PARAMETERS];
    }
}
