<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\SetUp;

use Assert\Assert;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use Pccomponentes\Ddd\Application\Command;

final class UserSetUpCommand extends Command
{
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_PLATFORM = 'platform';
    public const PAYLOAD_DATA = 'data';

    public const NAME = 'user_set_up';
    public const VERSION = '1';

    private UserReference $reference;
    private ClockInPlatform $platform;
    private array $data;

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
            ->keyExists(self::PAYLOAD_PLATFORM)
            ->keyExists(self::PAYLOAD_DATA)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->that($payload[self::PAYLOAD_PLATFORM], self::PAYLOAD_PLATFORM)->inArray(ClockInPlatform::allowedValues())
            ->that($payload[self::PAYLOAD_DATA], self::PAYLOAD_DATA)->isArray()
            ->verifyNow();

        foreach ($payload['data'] as $key => $datum) {
            Assert::lazy()
                ->that($key, self::PAYLOAD_DATA . $key . 'key')->string()->notBlank()
                ->that($datum, self::PAYLOAD_DATA . $key . 'value')->string()->notBlank()
                ->verifyNow();
        }

        $this->reference = UserReference::from($payload['reference']);
        $this->platform = ClockInPlatform::from($payload['platform']);
        $this->data = $payload['data'];
    }

    public function reference(): UserReference
    {
        return $this->reference;
    }

    public function platform(): ClockInPlatform
    {
        return $this->platform;
    }

    public function data(): array
    {
        return $this->data;
    }
}
