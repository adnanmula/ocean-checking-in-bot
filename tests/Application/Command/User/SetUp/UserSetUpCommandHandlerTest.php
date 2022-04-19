<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Tests\Application\Command\User\SetUp;

use AdnanMula\ClockInBot\Application\Command\User\SetUp\UserSetUpCommand;
use AdnanMula\ClockInBot\Application\Command\User\SetUp\UserSetUpCommandHandler;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Service\User\UserFinderByReference;
use AdnanMula\ClockInBot\Domain\Service\UserClientData\UserClientDataCreator;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsCreator;
use AdnanMula\ClockInBot\Domain\Service\UserSettings\UserSettingsRemoverByUserId;
use AdnanMula\ClockInBot\Tests\Mock\Domain\Model\User\UserObjectMother;
use Doctrine\DBAL\Connection;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UserSetUpCommandHandlerTest extends TestCase
{
    private MockObject $userRepository;
    private UserSetUpCommandHandler $handler;

    /** @test */
    public function given_valid_data_then_set_up(): void
    {
        $userId = Uuid::from('b99ca941-89b4-4ecc-9ecc-c48f46f15db2');
        $reference = '123456';
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $this->prepareUserRepository($userId, $reference);

        ($this->handler)($this->command($reference, $data));
    }

    protected function setUp(): void
    {
        $this->userRepository = $this->createMock(UserRepository::class);

        $this->handler = new UserSetUpCommandHandler(
            new UserFinderByReference($this->userRepository),
            $this->userRepository,
        );
    }

    private function prepareUserRepository(Uuid $id, string $reference): void
    {
        $provider = new UserObjectMother();
        $provider->setId($id);
        $provider->setReference($reference);
        $user = $provider->build();

        $this->userRepository->expects($this->once())
            ->method('byReference')
            ->with($reference)
            ->willReturn($user);
    }

    private function command(string $reference, array $data): UserSetUpCommand
    {
        return UserSetUpCommand::fromPayload(
            Uuid::v4(),
            [
                UserSetUpCommand::PAYLOAD_PLATFORM => 'ocean',
                UserSetUpCommand::PAYLOAD_REFERENCE => $reference,
                UserSetUpCommand::PAYLOAD_DATA => $data,
            ],
        );
    }
}
