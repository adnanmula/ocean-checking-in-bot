<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Model\User;

use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Model\User\ValueObject\UserEmail;
use DemigrantSoft\ClockInBot\Model\User\ValueObject\UserPassword;

final class User
{
    private Uuid $id;
    private UserEmail $email;
    private UserPassword $password;
    private UserSettings $settings;

    private function __construct(Uuid $id, UserEmail $email, UserPassword $password, UserSettings $settings)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->settings = $settings;
    }

    public static function create(Uuid $id, UserEmail $email, UserPassword $password, UserSettings $settings): self
    {
        return new self($id, $email, $password, $settings);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function settings(): UserSettings
    {
        return $this->settings;
    }
}
