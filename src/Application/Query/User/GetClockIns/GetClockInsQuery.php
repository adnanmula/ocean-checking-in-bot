<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetClockIns;

use Assert\Assert;
use AdnanMula\ClockInBot\Domain\Model\User\User;
use PcComponentes\Ddd\Application\Query;
use PcComponentes\Ddd\Domain\Model\ValueObject\DateTimeValueObject;

final class GetClockInsQuery extends Query
{
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_FROM = 'from';
    public const PAYLOAD_TO = 'to';

    private const VERSION = '1';
    private const NAME = 'get_clock_ins';

    private string $userReference;
    private ?DateTimeValueObject $from;
    private ?DateTimeValueObject $to;

    public static function messageName(): string
    {
        return 'adnanmula.'
            . 'clock-in-bot.'
            . self::messageVersion() . '.'
            . self::messageType() . '.'
            . User::modelName() . '.'
            . self::NAME;
    }

    public static function messageVersion(): string
    {
        return self::VERSION;
    }

    public function userReference(): string
    {
        return $this->userReference;
    }

    public function from(): ?DateTimeValueObject
    {
        return $this->from;
    }

    public function to(): ?DateTimeValueObject
    {
        return $this->to;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()->keyExists(self::PAYLOAD_REFERENCE)
            ->that($payload, 'payload')->isArray()->keyExists(self::PAYLOAD_FROM)
            ->that($payload, 'payload')->isArray()->keyExists(self::PAYLOAD_TO)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->uuid()
            ->that($payload[self::PAYLOAD_FROM], self::PAYLOAD_FROM)->nullOr()->date('Y-m-d H:i:s')
            ->that($payload[self::PAYLOAD_TO], self::PAYLOAD_TO)->nullOr()->date('Y-m-d H:i:s')
            ->verifyNow();

        $this->userReference = $payload[self::PAYLOAD_REFERENCE];

        $this->from = null !== $payload[self::PAYLOAD_FROM]
            ? DateTimeValueObject::from($payload[self::PAYLOAD_FROM])
            : null;

        $this->to = null !== $payload[self::PAYLOAD_TO]
            ? DateTimeValueObject::from($payload[self::PAYLOAD_TO])
            : null;
    }
}
