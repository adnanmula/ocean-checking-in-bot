<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetCheckInsCommand extends Command
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Get check ins')
            ->addArgument('from', InputOption::VALUE_REQUIRED)
            ->addArgument('to', InputOption::VALUE_OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $from = new \DateTimeImmutable($input->getArgument('from'));
        $to = $input->getArgument('to') ? new \DateTimeImmutable($input->getArgument('to')) : $from;

        $checkIns = $this->client->checkIns($from, $to);

        foreach ($checkIns['Detalles'] as $day) {
            $output->writeln("- Fichajes del " . $day['Fecha']);
            foreach ($day['Marcajes'] as $marcaje) {
                $output->writeln("  - " . $marcaje['MarcajeEntrada']['Hora'] . ' -> ' . $marcaje['MarcajeSalida']['Hora']);
            }
        }
    }
}
