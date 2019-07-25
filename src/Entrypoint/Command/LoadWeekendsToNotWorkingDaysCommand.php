<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Infrastructure\NotWorkingDays\Repository\NotWorkingDaysSqliteRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class LoadWeekendsToNotWorkingDaysCommand extends Command
{
    private $repository;

    public function __construct(NotWorkingDaysSqliteRepository $repository)
    {
        $this->repository = $repository;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Load weekends of given year to not working days db')
            ->addArgument('year', InputOption::VALUE_REQUIRED, 'Year to be loaded');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');

        $day = new \DateTime($year . '-01-01');
        while ($year === $day->format('Y')) {
            if (true === \in_array((int) ($day->format('N')), [6, 7])) {
                $this->repository->add($day);
                $output->writeln($day->format('Y-m-d') . ' added');
            }

            $day->add(new \DateInterval('P1D'));
        }
    }
}
