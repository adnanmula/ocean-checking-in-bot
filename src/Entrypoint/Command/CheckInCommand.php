<?php declare(strict_types=1);

namespace DemigrantSoft\Entrypoint\Command;

use DemigrantSoft\Domain\Client\Client;
use DemigrantSoft\Domain\Notification\NotificationService;
use DemigrantSoft\Domain\NotWorkingDays\Repository\NotWorkingDaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private const DEFAULT_MAX_WAIT = 600;

    private Client $client;
    private NotWorkingDaysRepository $repository;
    private NotificationService $notificationService;

    public function __construct(Client $client, NotWorkingDaysRepository $repository, NotificationService $notificationService)
    {
        $this->client = $client;
        $this->repository = $repository;
        $this->notificationService = $notificationService;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add checkin')
            ->addOption('random', 'r', InputOption::VALUE_OPTIONAL, 'Wait a random amount of minutes before checking in', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($this->repository->check(new \DateTimeImmutable())) {
            $output->writeln('Today is a not working day.');
            return 0;
        }

        $this->wait($input);

        $this->client->checkIn();
        $output->writeln('Succesfully checked in.');
        $this->notificationService->notify('Succesfully checked in.');

        return 0;
    }

    private function wait(InputInterface $input): void
    {
        if (true === $input->hasParameterOption(['--random', '-r'])) {
            $max = is_numeric($input->getOption('random'))
                ? (int) $input->getOption('random')
                : self::DEFAULT_MAX_WAIT;

            sleep(mt_rand(0, $max));
        }
    }
}
