<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetHelp;

use Assert\Assert;
use AdnanMula\ClockInBot\Domain\Model\User\User;
use PcComponentes\Ddd\Application\Query;

final class GetHelpQuery extends Query
{
    public const PAYLOAD_PLATFORM = 'platform';

    private const VERSION = '1';
    private const NAME = 'get_help';

    private string $platform;

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

    public function platform(): string
    {
        return $this->platform;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()->keyExists(self::PAYLOAD_PLATFORM)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_PLATFORM], self::PAYLOAD_PLATFORM)->string()
            ->verifyNow();

        $this->platform = $payload[self::PAYLOAD_PLATFORM];
    }
}
