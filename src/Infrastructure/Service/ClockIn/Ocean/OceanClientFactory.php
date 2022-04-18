<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\Ocean;

use GuzzleHttp\RequestOptions;

final class OceanClientFactory
{
    private string $baseUrl;

    public function build(
        string $baseUrl,
        string $user,
        string $password,
        float $latitude,
        float $longitude
    ): OceanClient {
        $this->baseUrl = $baseUrl;

        $data = $this->login($user, $password);

        if (null === $data->Token && null !== $data->TokenDesbloqueo) {
            $this->unlock($data->Usuario->Id, $data->TokenDesbloqueo, $data->Usuario->EmpresaId);
            $data = $this->login($user, $password);
        }

        return new OceanClient(
            [
                'base_uri' => $baseUrl,
                'headers' => ['Authorization' => 'Bearer ' . $data->Token],
            ],
            $latitude,
            $longitude,
        );
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
            ],
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
            ],
        ]);
    }
}
