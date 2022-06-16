<?php declare(strict_types=1);

namespace AdnanMula\ClockInBot\Tests\Application\Command\User\Register;

use AdnanMula\ClockInBot\Application\Command\User\Register\UserRegisterCommand;
use AdnanMula\ClockInBot\Application\Command\User\Register\UserRegisterCommandHandler;
use AdnanMula\ClockInBot\Domain\Model\User\Exception\UserAlreadyExistsException;
use AdnanMula\ClockInBot\Domain\Model\User\UserRepository;
use AdnanMula\ClockInBot\Domain\Service\User\UserCreator;
use AdnanMula\ClockInBot\Tests\Mock\Domain\Model\User\UserObjectMother;
use PcComponentes\Ddd\Domain\Model\ValueObject\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class UserRegisterCommandHandlerTest extends TestCase
{
    private MockObject $repository;
    private UserRegisterCommandHandler $handler;

    /** @test */
    public function given_not_existing_user_then_create(): void
    {
        $provider = new UserObjectMother();
        $user = $provider->build();

        $this->repository->expects($this->once())
            ->method('byReference')
            ->with($user->reference())
            ->willReturn(null);

        $this->repository->expects($this->once())->method('save');

        ($this->handler)($this->command($user->reference()));
    }

    /** @test */
    public function given_existing_user_then_throw_exception(): void
    {
        $this->expectException(UserAlreadyExistsException::class);

        $provider = new UserObjectMother();
        $user = $provider->build();

        $this->repository->expects($this->once())
            ->method('byReference')
            ->with($user->reference())
            ->willReturn($user);

        $this->repository->expects($this->never())->method('save');

        ($this->handler)($this->command($user->reference()));
    }

    protected function setUp(): void
    {
        $this->repository = $this->createMock(UserRepository::class);

        $this->handler = new UserRegisterCommandHandler(
            new UserCreator($this->repository),
        );
    }

    private function command(string $userReference): UserRegisterCommand
    {
        return UserRegisterCommand::fromPayload(
            Uuid::v4(),
            [
                UserRegisterCommand::PAYLOAD_REFERENCE => $userReference,
                UserRegisterCommand::PAYLOAD_USERNAME => 'username',
            ],
        );
    }
}
