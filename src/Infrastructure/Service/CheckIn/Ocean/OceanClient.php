<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Infrastructure\Service\CheckIn\Ocean;

use DemigrantSoft\ClockInBot\Domain\Model\Client\Client;
use GuzzleHttp\RequestOptions;

final class OceanClient extends \GuzzleHttp\Client implements Client
{
    public const PLATFORM = 'ocean';
    public const VERSION = '';

    private float $latitude;
    private float $longitude;

    public function __construct(
        array $config,
        float $latitude,
        float $longitude
    ) {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        parent::__construct($config);
    }

    public function forceLogin(): \stdClass
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

    /** @return array[] */
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

    public function platform(): string
    {
        return self::PLATFORM;
    }

    public function version(): string
    {
        return self::VERSION;
    }
}
