<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use App\Domain\Notification\NotificationService;
use App\Domain\NotWorkingDays\Repository\NotWorkingDaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private const DEFAULT_MAX_WAIT = 600;

    private $client;
    private $repository;
    private $notificationService;

    public function __construct(Client $client, NotWorkingDaysRepository $repository, NotificationService $notificationService)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->notificationService = $notificationService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add checkin')
            ->addOption('random', 'r', InputOption::VALUE_OPTIONAL, 'Wait a random amount of minutes before checking in', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->repository->check(new \DateTimeImmutable())) {
            $output->writeln('Today is a not working day.');
            return;
        }

        $this->wait($input);

        $this->client->checkIn();
        $output->writeln('Succesfully checked in.');
        $this->notificationService->notify('Succesfully checked in.');
    }

    private function wait(InputInterface $input)
    {
        if (true === $input->hasParameterOption(['--random', '-r'])) {
            $max = is_numeric($input->getOption('random'))
                ? (int) $input->getOption('random')
                : self::DEFAULT_MAX_WAIT;

            sleep(mt_rand(0, $max));
        }
    }
}
