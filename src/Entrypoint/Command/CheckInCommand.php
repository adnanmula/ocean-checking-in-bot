<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Model\NotWorkingDay\NotWorkingDaysRepository;
use DemigrantSoft\ClockInBot\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Service\Notification\NotificationService;
use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\User\UserDbalRepository;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientData;
use DemigrantSoft\ClockInBot\Infrastructure\Service\ClockIn\ClientFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private const DEFAULT_MAX_WAIT = 600;

    private NotWorkingDaysRepository $repository;
    private NotificationService $notificationService;
    private UserDbalRepository $userRepository;
    private ClientFactory $clientFactory;

    public function __construct(
        UserDbalRepository $userRepository,
        NotificationService $notificationService,
        NotWorkingDaysRepository $repository,
        ClientFactory $clientFactory
    ) {
        $this->userRepository = $userRepository;
        $this->repository = $repository;
        $this->notificationService = $notificationService;
        $this->clientFactory = $clientFactory;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Add checkin')
            ->addOption('user', 'u', InputOption::VALUE_REQUIRED, 'User id', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('user')
            ? Uuid::from($input->getArgument('user'))
            : null;

        if (null === $userId) {
            throw new \Exception();
        }

        $user = $this->userRepository->byId($userId);

        $client = $this->clientFactory->build($user->settings()->client(), ClientData::from([]));



        if ($this->repository->check($user->id(), new \DateTimeImmutable())) {
            $output->writeln('Today is a not working day.');
            return 0;
        }

//        $input
        $this->wait($user->settings()->schedule());

        $client->checkIn();
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
