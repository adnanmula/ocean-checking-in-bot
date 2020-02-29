<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Domain\Model\User;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserEmail;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;

final class User
{
    private Uuid $id;
    private UserReference $reference;
    private UserEmail $email;
    private UserPassword $password;
    private UserSettings $settings;

    private function __construct(Uuid $id, UserReference $reference, UserEmail $email, UserPassword $password, UserSettings $settings)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->settings = $settings;
        $this->reference = $reference;
    }

    public static function create(Uuid $id, UserReference $reference, UserEmail $email, UserPassword $password, UserSettings $settings): self
    {
        return new self($id, $reference, $email, $password, $settings);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function reference(): UserReference
    {
        return $this->reference;
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
