<?php

namespace Ulatest\Test;

use Hamcrest\Core\IsEqual;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Mockery\MockInterface;
use SimpleBus\Message\Bus\Middleware\FinishesHandlingMessageBeforeHandlingNext;
use SimpleBus\Message\Bus\Middleware\MessageBusSupportingMiddleware;
use SimpleBus\Message\CallableResolver\CallableMap;
use SimpleBus\Message\CallableResolver\ServiceLocatorAwareCallableResolver;
use SimpleBus\Message\Handler\DelegatesToMessageHandlerMiddleware;
use SimpleBus\Message\Handler\Resolver\NameBasedMessageHandlerResolver;
use SimpleBus\Message\Name\ClassBasedNameResolver;
use Ulatest\Domain\Subscriber\Subscriber;
use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberRepository;

class UlatestUnitTestCase extends MockeryTestCase
{
    /** @var  MessageBusSupportingMiddleware */
    private $bus;

    /** @var  SubscriberRepository|MockInterface */
    private $repository;

    protected function getSubscriberRepository()
    {
        return $this->repository = $this->repository ?: \Mockery::mock(SubscriberRepository::class);
    }

    protected function shouldSearchSubscriber(SubscriberEmail $subscriberEmail, Subscriber $subscriber = null)
    {
        $this->getSubscriberRepository()
            ->shouldReceive('searchByEmail')
            ->once()
            ->with(IsEqual::equalTo($subscriberEmail))
            ->andReturn($subscriber);
    }

    protected function shouldSaveSubscriber(Subscriber $subscriber)
    {
        $this->getSubscriberRepository()
            ->shouldReceive('save')
            ->once()
            ->with(IsEqual::equalTo($subscriber))
            ->andReturnNull();
    }

    protected function publishToBus($command, $handler)
    {
        $this->setUpCommandBus($command, $handler);

        $this->bus->handle($command);
    }

    private function setUpCommandBus($command, $handler)
    {
        $this->bus = new MessageBusSupportingMiddleware();
        $this->bus->appendMiddleware(new FinishesHandlingMessageBeforeHandlingNext());
        $commandHandlersByCommandName = [
            get_class($command) => $handler,
        ];

        $serviceLocator = function ($serviceId) {
            return $serviceId;
        };

        $commandHandlerMap = new CallableMap(
            $commandHandlersByCommandName,
            new ServiceLocatorAwareCallableResolver($serviceLocator)
        );

        $commandNameResolver = new ClassBasedNameResolver();

        $commandHandlerResolver = new NameBasedMessageHandlerResolver(
            $commandNameResolver,
            $commandHandlerMap
        );

        $this->bus->appendMiddleware(
            new DelegatesToMessageHandlerMiddleware(
                $commandHandlerResolver
            )
        );
    }
}
