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
    public const VERSION = '1.7.0211.0';

    private float $latitude;
    private float $longitude;

    public function __construct(array $config, float $latitude, float $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;

        parent::__construct($config);
    }

    public function platform(): string
    {
        return self::PLATFORM;
    }

    public function version(): string
    {
        return self::VERSION;
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

        $responseClockIns = Json::decode($response->getBody()->getContents())['Detalles'];
        $clockIns = [];

        foreach ($responseClockIns as $day) {
            foreach ($day['Marcajes'] as $clockIn) {
                if (\array_key_exists('MarcajeEntrada', $clockIn)) {
                    if (null === $clockIn['MarcajeEntrada']) {
                        continue;
                    }

                    $clockIns[] = ClockIn::from(
                        new \DateTimeImmutable($clockIn['MarcajeEntrada']['Hora']),
                        ClockInRandomness::from(0),
                    );
                }

                if (\array_key_exists('MarcajeSalida', $clockIn)) {
                    if (null === $clockIn['MarcajeSalida']) {
                        continue;
                    }

                    $clockIns[] = ClockIn::from(
                        new \DateTimeImmutable($clockIn['MarcajeSalida']['Hora']),
                        ClockInRandomness::from(0),
                    );
                }
            }
        }

        return ClockIns::from(...$clockIns);
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
