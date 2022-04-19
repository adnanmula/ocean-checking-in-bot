<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Infrastructure\Service\ClockIn\Ocean;

use AdnanMula\ClockInBot\Domain\Model\Client\Client;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIn;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockInRandomness;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\ClockIns;
use AdnanMula\ClockInBot\Util\Json;
use GuzzleHttp\RequestOptions;

final class OceanClient extends \GuzzleHttp\Client implements Client
{
    public const PLATFORM = 'ocean';
    public const VERSION = '';

    private float $latitude;
    private float $longitude;

    public function __construct(array $config, float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        parent::__construct($config);
    }

    public function clockIn(): void
    {
        $this->post(
            '/data/marcajes/realizar-manual',
            [
                RequestOptions::JSON => [
                    'GeoLat' => $this->latitude,
                    'GeoLong' => $this->longitude,
                    'IncidenciaId' => null,
                    'Nota' => null,
                    'Tipo' => 'P',
                    'TipoProd' => 1,
                ],
            ],
        );
    }

    public function clockIns(\DateTimeInterface $from, \DateTimeInterface $to): ClockIns
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
            ],
        );

        $clockIns = [];

        foreach (Json::decode($response->getBody()->getContents()) as $day) {
            foreach ($day['Marcajes'] as $marcaje) {
                if (\array_key_exists('MarcajeEntrada', $marcaje)) {
                    $clockIns[] = ClockIn::from(
                        new \DateTimeImmutable($marcaje['MarcajeEntrada']['Hora']),
                        ClockInRandomness::from(0),
                    );
                }

                if (\array_key_exists('MarcajeSalida', $marcaje)) {
                    $clockIns[] = ClockIn::from(
                        new \DateTimeImmutable($marcaje['MarcajeSalida']['Hora']),
                        ClockInRandomness::from(0),
                    );
                }
            }
        }

        return ClockIns::from($clockIns);
    }

    public function platform(): string
    {
        return self::PLATFORM;
    }

    public function version(): string
    {
        return self::VERSION;
    }

    private function forceLogin(): \stdClass
    {
        $login = $this->get(
            '/data/auth/actual',
            [
                RequestOptions::QUERY => [
                    'force' => true,
                ],
            ],
        );

        return \json_decode($login->getBody()->getContents());
    }
}
