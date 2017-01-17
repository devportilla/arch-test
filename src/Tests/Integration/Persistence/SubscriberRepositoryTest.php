<?php

namespace Ulatest\Tests\Integration\Persistence;

use Ulatest\Domain\Subscriber\SubscriberRepository;
use Ulatest\Test\Stub\SubscriberEmailStub;
use Ulatest\Test\Stub\SubscriberIdStub;
use Ulatest\Test\Stub\SubscriberStub;
use Ulatest\Test\UlatestFunctionalTestCase;

final class SubscriberRepositoryTest extends UlatestFunctionalTestCase
{
    /** @var SubscriberRepository */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = $this->getSubscriberRepository();
    }

    /** @test */
    public function it_should_save_subscriber()
    {
        $subscriberId    = SubscriberIdStub::random();
        $subscriberEmail = SubscriberEmailStub::random();
        $subscriber      = SubscriberStub::create($subscriberId, $subscriberEmail);

        $this->repository->save($subscriber);
        $this->clearUnitOfWork();

        $this->assertEquals($subscriber, $this->repository->searchByEmail($subscriberEmail));
    }

    /** @test */
    public function it_should_not_find_a_non_existing_subscriber()
    {
        $subscriber = SubscriberStub::random();

        $this->assertNull($this->repository->searchByEmail($subscriber->email()));
    }
}
