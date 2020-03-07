<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\SimpleAggregateRoot;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserId;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserUsername;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;

final class User extends SimpleAggregateRoot
{
    private const MODEL_NAME = 'user';

    private UserReference $reference;
    private UserUsername $username;
    private UserSettings $settings;

    public static function create(UserId $id, UserReference $reference, UserUsername $username, UserSettings $settings): self
    {
        $self = new self($id);
        $self->reference = $reference;
        $self->username = $username;
        $self->settings = $settings;

        return $self;
    }

    public function reference(): UserReference
    {
        return $this->reference;
    }

    public function username(): UserUsername
    {
        return $this->username;
    }

    public function settings(): UserSettings
    {
        return $this->settings;
    }

    public static function modelName(): string
    {
        return self::MODEL_NAME;
    }
}
