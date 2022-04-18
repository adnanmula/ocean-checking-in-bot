<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Application\Query\User\GetConfig;

use Assert\Assert;
use AdnanMula\ClockInBot\Domain\Model\User\User;
use AdnanMula\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use PcComponentes\Ddd\Application\Query;

final class GetConfigQuery extends Query
{
    public const PAYLOAD_REFERENCE = 'reference';

    private const VERSION = '1';
    private const NAME = 'get_config';

    private UserReference $userReference;

    public static function messageName(): string
    {
        return 'pccomponentes.'
            . 'offer.'
            . self::messageVersion() . '.'
            . self::messageType() . '.'
            . User::modelName() . '.'
            . self::NAME;
    }

    public static function messageVersion(): string
    {
        return self::VERSION;
    }

    protected function assertPayload(): void
    {
        $payload = $this->messagePayload();

        Assert::lazy()
            ->that($payload, 'payload')->isArray()->keyExists(self::PAYLOAD_REFERENCE)
            ->verifyNow();

        Assert::lazy()
            ->that($payload[self::PAYLOAD_REFERENCE], self::PAYLOAD_REFERENCE)->string()->notBlank()
            ->verifyNow();

        $this->userReference = UserReference::from($payload[self::PAYLOAD_REFERENCE]);
    }

    public function userReference(): UserReference
    {
        return $this->userReference;
    }
}
