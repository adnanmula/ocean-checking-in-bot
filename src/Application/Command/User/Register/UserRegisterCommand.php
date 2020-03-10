<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\Command\User\Register;

use Assert\Assert;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use Pccomponentes\Ddd\Application\Command;

final class UserRegisterCommand extends Command
{
    public const PAYLOAD_ID = 'id';
    public const PAYLOAD_REFERENCE = 'reference';
    public const PAYLOAD_USERNAME = 'username';

    public const NAME = 'user_register';
    public const VERSION = '1';

    private UserId $id;
    private UserReference $reference;
    private UserUsername $username;

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
            ->that($payload, 'payload')
            ->isArray()
            ->keyExists(self::PAYLOAD_ID)
            ->keyExists(self::PAYLOAD_REFERENCE)
            ->keyExists(self::PAYLOAD_USERNAME)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_ID], self::PAYLOAD_ID)->uuid()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->that($payload[self::PAYLOAD_USERNAME], self::PAYLOAD_USERNAME)->string()->notBlank()
            ->verifyNow();

        $this->id = UserId::from($payload[self::PAYLOAD_ID]);
        $this->reference = UserReference::from($payload[self::PAYLOAD_REFERENCE]);
        $this->username = UserUsername::from($payload[self::PAYLOAD_USERNAME]);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function reference(): UserReference
    {
        return $this->reference;
    }

    public function username(): UserUsername
    {
        return $this->username;
    }
}
