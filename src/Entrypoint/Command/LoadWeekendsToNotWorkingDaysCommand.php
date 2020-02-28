<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Model\NotWorkingDay\NotWorkingDaysRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadWeekendsToNotWorkingDaysCommand extends Command
{
    private NotWorkingDaysRepository $repository;

    public function __construct(NotWorkingDaysRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Load weekends of given year to not working days db')
            ->addArgument('year', InputOption::VALUE_REQUIRED, 'Year to be loaded');;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $year = $input->getArgument('year');

        $day = new \DateTime((string) $year . '-01-01');
        while ($year === $day->format('Y')) {
            if (true === \in_array((int) ($day->format('N')), [6, 7])) {
                $this->repository->add($day);
                $output->writeln($day->format('Y-m-d') . ' added');
            }

            $day->add(new \DateInterval('P1D'));
        }

        return 0;
    }
}
