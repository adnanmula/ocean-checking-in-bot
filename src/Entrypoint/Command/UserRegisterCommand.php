<?php declare(strict_types=1);

namespace DemigrantSoft\ClockInBot\Entrypoint\Command;

use DemigrantSoft\ClockInBot\Domain\Model\Shared\ValueObject\Uuid;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\UserSettings;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInMode;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInSchedule;
use DemigrantSoft\ClockInBot\Domain\Model\User\Aggregate\Settings\ValueObject\ClockInPlatform;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserEmail;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserPassword;
use DemigrantSoft\ClockInBot\Domain\Model\User\ValueObject\UserReference;
use DemigrantSoft\ClockInBot\Domain\Service\User\UserCreator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class UserRegisterCommand extends Command
{
    private UserCreator $creator;

    public function __construct(UserCreator $creator)
    {
        $this->creator = $creator;

        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Register user.')
            ->addArgument('reference', InputOption::VALUE_REQUIRED)
            ->addArgument('email', InputOption::VALUE_REQUIRED)
            ->addArgument('password', InputOption::VALUE_REQUIRED)
            ->addArgument('platform', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $reference = $input->getArgument('reference');
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');
        $platform = $input->getArgument('platform');

        $this->creator->execute(
            Uuid::v4(),
            UserReference::from($reference),
            UserEmail::from($email),
            UserPassword::from($password),
            UserSettings::from(
                ClockInPlatform::from($platform),
                ClockInMode::from(ClockInMode::MODE_MANUAL),
                ClockInSchedule::from()
            )
        );

        return 0;
    }
}
