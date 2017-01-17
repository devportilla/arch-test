<?php

namespace Ulatest\Tests\Behaviour\Subscriber;

use Ulatest\Domain\Subscriber\Add\SubscriberAddCommandHandler;
use Ulatest\Domain\Subscriber\Add\SubscriberAdder;
use Ulatest\Domain\Subscriber\Add\SubscriberAlreadyExists;
use Ulatest\Domain\Subscriber\Subscriber;
use Ulatest\Test\Stub\SubscriberAddCommandStub;
use Ulatest\Test\Stub\SubscriberEmailStub;
use Ulatest\Test\Stub\SubscriberIdStub;
use Ulatest\Test\Stub\SubscriberStub;
use Ulatest\Test\UlatestUnitTestCase;

final class SubscriberAddTest extends UlatestUnitTestCase
{
    private $handler;

    public function setUp()
    {
        parent::setUp();

        $subscriberAdder = new SubscriberAdder($this->getSubscriberRepository());
        $this->handler   = new SubscriberAddCommandHandler($subscriberAdder);
    }

    /** @test */
    public function it_should_add_a_subscriber()
    {
        $subscriberId    = SubscriberIdStub::random();
        $subscriberEmail = SubscriberEmailStub::random();

        $subscriber = SubscriberStub::create($subscriberId, $subscriberEmail);

        $command = SubscriberAddCommandStub::create($subscriberId, $subscriberEmail);

        $this->shouldSearchSubscriber($subscriberEmail);
        $this->shouldSaveSubscriber($subscriber);

        $this->publishToBus($command, $this->handler);
    }

    /** @test */
    public function it_should_throw_an_exception_when_subscribing_an_existing_subscriber()
    {
        $subscriberId    = SubscriberIdStub::random();
        $subscriberEmail = SubscriberEmailStub::random();

        $subscriber = new Subscriber($subscriberId, $subscriberEmail);

        $command = SubscriberAddCommandStub::create($subscriberId, $subscriberEmail);

        $this->shouldSearchSubscriber($subscriberEmail, $subscriber);
        $this->expectException(SubscriberAlreadyExists::class);

        $this->publishToBus($command, $this->handler);
    }
}
