<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn;

use DemigrantSoft\ClockInBot\Domain\Model\Client\Client;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInData;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\Ocean\OceanClientFactory;

final class ClientFactory
{
    private OceanClientFactory $oceanFactory;

    public function __construct(OceanClientFactory $oceanFactory)
    {
        $this->oceanFactory = $oceanFactory;
    }

    public function build(ClockInPlatform $client, ClockInData $data): Client
    {
        if ($client->isOcean()) {
            $this->oceanFactory->build(
                $data->baseUrl(),
                $data->user(),
                $data->password(),
                $data->latitude(),
                $data->longitude()
            );
        }

        throw new ClientNotFoundException();
    }
}
