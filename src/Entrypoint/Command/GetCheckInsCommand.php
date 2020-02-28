<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Model\Client\Client;
use DemigrantSoft\ClockInBot\Service\Notification\NotificationService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class GetCheckInsCommand extends Command
{
    private Client $client;
    private NotificationService $notificationService;

    public function __construct(Client $client, NotificationService $notificationService)
    {
        $this->client = $client;
        $this->notificationService = $notificationService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Get check ins')
            ->addArgument('from', InputOption::VALUE_REQUIRED)
            ->addArgument('to', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $from = $input->getArgument('from') ? new \DateTimeImmutable($input->getArgument('from')) :  new \DateTimeImmutable();
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

        return 0;
    }
}
