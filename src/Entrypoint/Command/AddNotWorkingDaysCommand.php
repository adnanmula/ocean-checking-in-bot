<?php declare(strict_types=1);

namespace DemigrantSoft\Entrypoint\Command;

use DemigrantSoft\Infrastructure\NotWorkingDays\Repository\NotWorkingDaysSqliteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class AddNotWorkingDaysCommand extends Command
{
    private NotWorkingDaysSqliteRepository $repository;

    public function __construct(NotWorkingDaysSqliteRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Add dates to not working days')
            ->addArgument('dates', InputOption::VALUE_REQUIRED, 'Comma separated dates to be added as not working days');;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $dates = \array_map('trim', \array_filter(\explode(',', $input->getArgument('dates'))));

        foreach ($dates as $date) {
            $this->repository->add(new \DateTimeImmutable($date));
            $output->writeln($date . ' added');
        }

        return 0;
    }
}
