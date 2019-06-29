<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private $baseUrl;
    private $checkInEndpoint;
    private $user;
    private $password;

    public function __construct(string $baseUrl, string $checkInEndpoint, string $user, string $pass)
    {
        $this->baseUrl = $baseUrl;
        $this->checkInEndpoint = $checkInEndpoint;
        $this->user = $user;
        $this->password = $pass;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = new Client();

        $login = $client->post('url', [
            RequestOptions::JSON => [
                'Login' => $this->user,
                'Password' => $this->password,
                'ConnId' => null,
                'SSOId' => null,
                'Ldap' => false,
            ]
        ]);
    }
}
