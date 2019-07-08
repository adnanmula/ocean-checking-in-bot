<?php declare(strict_types=1);

namespace App\Domain\Client;

use GuzzleHttp\RequestOptions;

class Client extends \GuzzleHttp\Client
{
    private $baseUrl;
    private $user;
    private $password;
    private $latitude;
    private $longitude;

    public function __construct(
        string $baseUrl,
        string $user,
        string $password,
        float $latitude,
        float $longitude
    ) {
        $this->baseUrl = $baseUrl;
        $this->user = $user;
        $this->password = $password;
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        parent::__construct(['base_uri' => $this->baseUrl]);
    }

    public function login(): \stdClass
    {
        $login = $this->post('/data/auth', [
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
        $this->post('/data/auth/unlock', [
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

    public function checkIn(\stdClass $data): void
    {
        $this->post(
            '/data/marcajes/realizar-manual', [
                RequestOptions::JSON => [
                    'GeoLat' => $this->latitude,
                    'GeoLong' => $this->longitude,
                    'IncidenciaId' => null,
                    'Nota' => null,
                    'Tipo' => 'P',
                    'TipoProd' => 1,
                ],
                RequestOptions::HEADERS => [
                    'origin' => $this->baseUrl,
                    'pragma' => 'no-cache',
                    'referer' => $this->baseUrl,
                    'user-agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
                    'authorization' => 'Bearer ' . $data->Token
                ]
            ]
        );
    }
}
