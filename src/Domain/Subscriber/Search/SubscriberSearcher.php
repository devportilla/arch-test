<?php

namespace Ulatest\Domain\Subscriber\Search;

use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberRepository;

final class SubscriberSearcher
{
    private $repository;

    public function __construct(SubscriberRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(SubscriberEmail $subscriberEmail)
    {
        return $this->repository->searchByEmail($subscriberEmail);
    }
}
