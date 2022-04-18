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
    private MockObject $connection;
    private MockObject $userRepository;
    private MockObject $userSettingsRepository;
    private MockObject $userClientDataRepository;

    private UserSetUpCommandHandler $handler;

    /** @test */
    public function given_valid_data_then_set_up()
    {
        $userId = Uuid::from('b99ca941-89b4-4ecc-9ecc-c48f46f15db2');
        $reference = '123456';
        $data = [
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        ];

        $this->prepareUserRepository($userId, $reference);
        $this->prepareSettingsRepository($userId);
        $this->prepareDataRepository($userId, $data);

        ($this->handler)($this->command($reference, $data));
    }

    protected function setUp(): void
    {
        $this->connection = $this->createMock(Connection::class);
        $this->userRepository = $this->createMock(UserRepository::class);
        $this->userSettingsRepository = $this->createMock(UserSettingsRepository::class);
        $this->userClientDataRepository = $this->createMock(UserClientDataRepository::class);

        $this->handler = new UserSetUpCommandHandler(
            $this->connection,
            new UserFinderByReference($this->userRepository),
            new UserSettingsRemoverByUserId($this->userSettingsRepository),
            new UserSettingsCreator($this->userSettingsRepository),
            new UserClientDataCreator($this->userClientDataRepository),
        );
    }

    private function prepareUserRepository(Uuid $id, string $reference)
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

    private function prepareSettingsRepository(Uuid $userId)
    {
        $settingsProvider = new UserSettingsMockProvider();
        $settingsProvider->setUserId($userId);
        $settings = $settingsProvider->build();

        $this->userSettingsRepository->expects($this->exactly(2))
            ->method('byUserId')
            ->with($userId)
            ->willReturnOnConsecutiveCalls($settings, null);

        $this->userSettingsRepository->expects($this->once())
            ->method('removeByUserId')
            ->with($userId);

        $this->userSettingsRepository->expects($this->once())->method('save');
    }

    private function prepareDataRepository(Uuid $userId, array $data)
    {
        $dataProvider = new UserClientDataMockProvider();
        $dataProvider->setUserId($userId);
        $userData = $dataProvider->build();

        $this->userClientDataRepository->expects($this->once())
            ->method('byUserId')
            ->with($userId)
            ->willReturn($userData);

        $dataProvider->addData($data);
        $newUserData = $dataProvider->build();

        $this->userClientDataRepository->expects($this->once())
            ->method('save')
            ->with($newUserData);
    }

    private function command(UserReference $reference, array $data): UserSetUpCommand
    {
        return UserSetUpCommand::fromPayload(
            Uuid::v4(),
            [
                UserSetUpCommand::PAYLOAD_PLATFORM => 'ocean',
                UserSetUpCommand::PAYLOAD_REFERENCE => $reference->value(),
                UserSetUpCommand::PAYLOAD_DATA => $data,
            ],
        );
    }
}
