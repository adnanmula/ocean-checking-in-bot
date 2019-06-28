<?php declare(strict_types=1);

namespace App\Entrypoint\Command;

use App\Domain\Client\Client;
use GuzzleHttp\RequestOptions;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CheckInCommand extends Command
{
    private $loginUrl;
    private $checkInUrl;
    private $user;
    private $password;

    public function __construct(string $loginUrl, string $checkInUrl, string $user, string $password)
    {
        parent::__construct();
        $this->loginUrl = $loginUrl;
        $this->checkInUrl = $checkInUrl;
        $this->user = $user;
        $this->password = $password;
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
