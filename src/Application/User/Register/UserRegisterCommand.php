<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Application\User\Register;

use Assert\Assert;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use Pccomponentes\Ddd\Application\Command;

final class UserRegisterCommand extends Command
{
    public const NAME = 'user_create';
    public const VERSION = '1';

    private UserId $id;
    private UserReference $reference;
    private UserUsername $username;
    private UserPassword $password;
    private ClockInPlatform $platform;

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
            ->keyExists('id')
            ->keyExists('reference')
            ->keyExists('username')
            ->keyExists('pass')
            ->keyExists('platform')
            ->verifyNow();

        Assert::lazy()
            ->that($payload['id'], 'id')->uuid()
            ->that($payload['reference'], 'reference')->string()->notBlank()
            ->that($payload['username'], 'username')->string()->notBlank()
            ->that($payload['password'], 'password')->string()->notBlank()
            ->that($payload['platform'], 'platform')->string()->notBlank()
            ->verifyNow();

        $this->id = UserId::from($payload['id']);
        $this->reference = UserReference::from($payload['reference']);
        $this->username = UserUsername::from($payload['username']);
        $this->password = UserPassword::from($payload['password']);
        $this->platform = ClockInPlatform::from($payload['platform']);
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

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function platform(): ClockInPlatform
    {
        return $this->platform;
    }
}
