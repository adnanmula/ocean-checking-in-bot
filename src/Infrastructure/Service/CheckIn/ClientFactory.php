<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\CheckIn;

use DemigrantSoft\ClockInBot\Domain\Model\Client\Client;
use DemigrantSoft\ClockInBot\Model\User\Aggregate\Settings\ValueObject\UserClient;

final class ClientFactory
{
    private OceanClientFactory $oceanFactory;

    public function __construct(OceanClientFactory $oceanFactory)
    {
        $this->oceanFactory = $oceanFactory;
    }

    public function build(UserClient $client, ClientData $data): Client
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
