<?php declare(strict_types=1);

namespace App\Domain\Client;

use GuzzleHttp\RequestOptions;

class Client extends \GuzzleHttp\Client
{
    private $baseUrl;
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
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        //TODO fix this
        $data = $this->login($user, $password);

        if ($data->Token === null && $data->TokenDesbloqueo !== null) {
            $this->unlock($data->Usuario->Id, $data->TokenDesbloqueo, $data->Usuario->EmpresaId);
            $data = $this->login($user, $password);
        }

        parent::__construct([
            'base_uri' => $baseUrl,
            'headers' => ['Authorization' => 'Bearer ' . $data->Token]
        ]);
    }

    private function login(string $user, string $password): \stdClass
    {
        $client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

        $login = $client->post('/data/auth', [
            RequestOptions::JSON => [
                'Login' => $user,
                'Password' => $password,
                'ConnId' => null,
                'SSOId' => null,
                'Ldap' => false,
            ]
        ]);

        return json_decode($login->getBody()->getContents());
    }

    private function unlock(int $userId, string $unlockToken, int $corporationId): void
    {
        $client = new \GuzzleHttp\Client(['base_uri' => $this->baseUrl]);

        $client->post('/data/auth/unlock', [
            RequestOptions::JSON => [
                'usuarioId' => $userId,
                'tokenDesbloqueo' => $unlockToken,
                'empresaId' => $corporationId,
            ]
        ]);
    }

    private function forceLogin(): \stdClass
    {
        $login = $this->get(
            '/data/auth/actual',
            [
                RequestOptions::QUERY => [
                    'force' => true,
                ],
            ]
        );

        return json_decode($login->getBody()->getContents());
    }

    public function checkIn(): void
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
                ]
            ]
        );
    }

    public function checkIns(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        $data = $this->forceLogin();

        $response = $this->get(
            '/data/marcajes',
            [
                RequestOptions::QUERY => [
                    'Desde' => $from->format('Y-m-d'),
                    'EmpleadoId' => $data->Usuario->Id,
                    'Hasta' => $to->format('Y-m-d'),
                    'Tipo' => 'P',
                ],
            ]
        );

        return json_decode($response->getBody()->getContents(), true);
    }
}
