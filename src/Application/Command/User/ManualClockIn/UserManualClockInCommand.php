<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\ManualClockIn;

use Assert\Assert;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use Pccomponentes\Ddd\Application\Command;

final class UserManualClockInCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';

    public const NAME = 'user_manual_clock_in';
    public const VERSION = '1';

    private UserReference $reference;

    public static function messageName(): string
    {
        return self::NAME;
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

        $this->reference = UserReference::from(self::PAYLOAD_REFERENCE);
    }

    public function reference(): UserReference
    {
        return $this->reference;
    }
}
