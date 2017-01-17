<?php

namespace Ulatest\App\Commands;

use SimpleBus\Message\Bus\MessageBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ulatest\Domain\Subscriber\Add\SubscriberAddCommand;
use Ulatest\Domain\Subscriber\SubscriberIdGenerator;

class UserSubscribe extends Command
{
    private $commandBus;
    private $idGenerator;

    public function __construct(MessageBus $commandBus, SubscriberIdGenerator $idGenerator)
    {
        parent::__construct();
        $this->commandBus  = $commandBus;
        $this->idGenerator = $idGenerator;
    }

    protected function configure()
    {
        $this
            ->setName('user:subscribe')
            ->setDescription('Subscribe user to the newsletter')
            ->addArgument('email', InputArgument::REQUIRED, 'User email to subscribe');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email   = $input->getArgument('email');
        $id      = $this->idGenerator->id();
        $command = new SubscriberAddCommand($id, $email);
        try {
            $this->commandBus->handle($command);
            $output->write(sprintf('<info>User with id %s and email %s created</info>', 'id',
                $input->getArgument('email')));
        } catch (\DomainException $exception) {
            $output->write(sprintf('<error>%s</error>', $exception->getMessage()));

            return 1;
        } catch (\InvalidArgumentException $exception) {
            $output->write(sprintf('<error>%s</error>', $exception->getMessage()));

            return 2;
        }
    }
}
