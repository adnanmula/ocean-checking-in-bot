<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use App\Domain\Notification\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetCheckInsCommand extends Command
{
    private $client;
    private $notificationService;

    public function __construct(Client $client, NotificationService $notificationService)
    {
        $this->client = $client;
        $this->notificationService = $notificationService;
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

        $msg = '';
        foreach ($checkIns['Detalles'] as $day) {
            $date = (new \DateTimeImmutable($day['Fecha']))->format('l, d-m-Y');

            $output->writeln("- Fichajes del " . $date);
            $msg .= "Fichajes del *" . $date . '*' . PHP_EOL;
            foreach ($day['Marcajes'] as $marcaje) {
                $output->writeln("  - " . $marcaje['MarcajeEntrada']['Hora'] . ' -> ' . $marcaje['MarcajeSalida']['Hora']);
                $msg .= "_- " . $marcaje['MarcajeEntrada']['Hora'] . ' -> ' . $marcaje['MarcajeSalida']['Hora'] . '_' . PHP_EOL;
            }
        }

        $this->notificationService->notify($msg);
    }
}
