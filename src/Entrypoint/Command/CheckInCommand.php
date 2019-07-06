<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use App\Infrastructure\NotWorkingDays\Repository\NotWorkingDaysSqliteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private $client;
    private $repository;

    public function __construct(Client $client, NotWorkingDaysSqliteRepository $repository)
    {
        $this->client = $client;
        $this->repository = $repository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->client->login();

        if ($data->Token === null && $data->TokenDesbloqueo !== null) {
            $this->client->unlock($data);
            $data = $this->client->login();
        }

        $this->client->checkIn($data);
    }
}
