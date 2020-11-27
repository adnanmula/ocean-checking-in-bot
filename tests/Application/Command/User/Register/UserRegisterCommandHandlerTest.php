<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Tests\Application\Command\User\Register;

use DemigrantSoft\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use DemigrantSoft\ClockInBot\Application\Command\User\Register\UserRegisterCommandHandler;
use DemigrantSoft\ClockInBot\Domain\Model\User\Exception\UserAlreadyExistsException;
use DemigrantSoft\ClockInBot\Domain\Model\User\UserRepository;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserCreator;
use DemigrantSoft\ClockInBot\Tests\Mock\Domain\Model\User\UserMockProvider;
use Pccomponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UserRegisterCommandHandlerTest extends TestCase
{
    private MockObject $repository;
    private UserRegisterCommandHandler $handler;

    /** @test */
    public function given_not_existing_user_then_create(): void
    {
        $provider = new UserMockProvider();
        $user = $provider->build();

        $this->repository->expects($this->once())
            ->method('byReference')
            ->with($user->reference())
            ->willReturn(null);

        $this->repository->expects($this->once())
            ->method('save')
            ->with();

        ($this->handler)($this->command($user->id(), $user->reference()));
    }

    /** @test */
    public function given_existing_user_then_throw_exception(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $provider = new UserMockProvider();
        $user = $provider->build();

        $this->repository->expects($this->once())
            ->method('byReference')
            ->with($user->reference())
            ->willReturn($user);

        $this->repository->expects($this->never())->method('save');

        ($this->handler)($this->command($user->id(), $user->reference()));
    }

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);

        $this->handler = new UserRegisterCommandHandler(
            new UserCreator($this->repository),
        );
    }

    private function command(Uuid $userId, UserReference $userReference): UserRegisterCommand
    {
        return UserRegisterCommand::fromPayload(
            Uuid::v4(),
            [
                UserRegisterCommand::PAYLOAD_ID => $userId->value(),
                UserRegisterCommand::PAYLOAD_REFERENCE => $userReference->value(),
                UserRegisterCommand::PAYLOAD_USERNAME => 'username',
            ],
        );
    }
}