<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Infrastructure\Persistence\Repository\User\UserDbalRepository;
use DemigrantSoft\ClockInBot\Model\NotWorkingDay\NotWorkingDaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddNotWorkingDaysCommand extends Command
{
    private NotWorkingDaysRepository $repository;
    private UserDbalRepository $userRepository;

    public function __construct(UserDbalRepository $userRepository, NotWorkingDaysRepository $repository)
    {
        $this->userRepository = $userRepository;
        $this->repository = $repository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Add dates to not working days')
            ->addArgument('user', InputOption::VALUE_REQUIRED, 'Id of the user to work with')
            ->addArgument('dates', InputOption::VALUE_REQUIRED, 'Comma separated dates to be added as not working days');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('user');



        $dates = \array_map('trim', \array_filter(\explode(',', $input->getArgument('dates'))));

        foreach ($dates as $date) {
            $this->repository->add(new \DateTimeImmutable($date));
            $output->writeln($date . ' added');
        }

        return 0;
    }
}
