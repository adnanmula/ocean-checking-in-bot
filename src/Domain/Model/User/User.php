<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Domain\Model\User;

use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserClientData;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSchedule;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserSettings;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;

final class User
{
    private const MODEL_NAME = 'user';

    private Uuid $id;
    private string $reference;
    private string $username;
    private ?UserSettings $settings;
    private ?UserClientData $clientData;
    private ?UserSchedule $schedule;

    private function __construct(
        Uuid $id,
        string $reference,
        string $username,
        ?UserSettings $settings,
        ?UserClientData $clientData,
        ?UserSchedule $schedule,
    ) {
        $this->id = $id;
        $this->reference = $reference;
        $this->username = $username;
        $this->settings = $settings;
        $this->clientData = $clientData;
        $this->schedule = $schedule;
    }

    public static function create(
        Uuid $id,
        string $reference,
        string $username,
        ?UserSettings $settings,
        ?UserClientData $clientData,
        ?UserSchedule $schedule,
    ): self {
        return new self($id, $reference, $username, $settings, $clientData, $schedule);
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function reference(): string
    {
        return $this->reference;
    }

    public function username(): string
    {
        return $this->username;
    }

    public function settings(): ?UserSettings
    {
        return $this->settings;
    }

    public function clientData(): ?UserClientData
    {
        return $this->clientData;
    }

    public function schedule(): ?UserSchedule
    {
        return $this->schedule;
    }

    public function updateSettings(?UserSettings $settings): void
    {
        $this->settings = $settings;
    }

    public function updateClientData(?UserClientData $clientData): void
    {
        $this->clientData = $clientData;
    }

    public function updateSchedule(?UserSchedule $schedule): void
    {
        $this->schedule = $schedule;
    }

    public static function modelName(): string
    {
        return self::MODEL_NAME;
    }
}
