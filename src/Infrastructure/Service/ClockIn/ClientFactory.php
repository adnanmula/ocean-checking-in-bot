<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\ClockIn;

use AdnanMula\ClockInBot\Domain\Model\Client\Client;
use AdnanMula\ClockInBot\Domain\Model\UserClientData\UserClientData;
use AdnanMula\ClockInBot\Domain\Model\UserSettings\ValueObject\ClockInPlatform;
use AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\Ocean\OceanClientFactory;

final class ClientFactory
{
    private OceanClientFactory $oceanFactory;

    public function __construct(OceanClientFactory $oceanFactory)
    {
        $this->oceanFactory = $oceanFactory;
    }

    public function build(ClockInPlatform $platform, UserClientData $data): Client
    {
        if ($platform->isOcean()) {
            return $this->oceanFactory->build(
                $data->baseUrl(),
                $data->user(),
                $data->password(),
                $data->latitude(),
                $data->longitude(),
            );
        }

        throw new ClientNotFoundException($platform->value());
    }
}
