<?php

namespace Ulatest\Domain\Subscriber\Add;

use Ulatest\Domain\Subscriber\Search\SubscriberSearcher;
use Ulatest\Domain\Subscriber\Subscriber;
use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberId;
use Ulatest\Domain\Subscriber\SubscriberRepository;

final class SubscriberAdder
{
    private $repository;
    /** @var SubscriberSearcher */
    private $searcher;

    public function __construct(SubscriberRepository $repository)
    {
        $this->repository = $repository;
        $this->searcher   = new SubscriberSearcher($repository);
    }

    public function __invoke(SubscriberId $subscriberId, SubscriberEmail $subscriberEmail)
    {
        $this->guardUserIsAlreadySubscribed($subscriberEmail);

        $subscriber = new Subscriber($subscriberId, $subscriberEmail);
        $this->repository->save($subscriber);

        //@TODO Launch events from here, u got them in the aggregate
        $subscriber->events();
    }

    private function guardUserIsAlreadySubscribed(SubscriberEmail $subscriberEmail)
    {
        if (null !== $this->searcher->__invoke($subscriberEmail)) {
            throw new SubscriberAlreadyExists($subscriberEmail);
        }
    }
}
