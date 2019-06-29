<?php declare(strict_types=1);

namespace App\Domain\Client;

use GuzzleHttp\RequestOptions;

class Client extends \GuzzleHttp\Client
{
    private $baseUrl;
    private $user;
    private $password;

    public function __construct(
        string $baseUrl,
        string $user,
        string $password
    ) {
        $this->baseUrl = $baseUrl;
        $this->user = $user;
        $this->password = $password;

        parent::__construct([]);
    }

    public function login(): \stdClass
    {
        $login = $this->post($this->baseUrl . '/data/auth', [
            RequestOptions::JSON => [
                'Login' => $this->user,
                'Password' => $this->password,
                'ConnId' => null,
                'SSOId' => null,
                'Ldap' => false,
            ],
            RequestOptions::HEADERS => [
                'origin' => $this->baseUrl,
                'pragma' => 'no-cache',
                'referer' => $this->baseUrl,
                'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
            ]
        ]);

        return json_decode($login->getBody()->getContents());
    }

    public function unlock(\stdClass $data): void
    {
        $this->post($this->baseUrl . '/data/auth/unlock', [
            RequestOptions::JSON => [
                'usuarioId' => $data->Usuario->Id,
                'tokenDesbloqueo' => $data->TokenDesbloqueo,
                'empresaId' => $data->Usuario->EmpresaId,
            ],
            RequestOptions::HEADERS => [
                'origin' => $this->baseUrl,
                'pragma' => 'no-cache',
                'referer' => $this->baseUrl,
                'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
            ]
        ]);
    }

    public function checkIn(\stdClass $data)
    {
        //TODO /data/marcajes/manual
    }
}
