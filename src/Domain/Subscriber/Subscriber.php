<?php

namespace Ulatest\Domain\Subscriber;

use Ulatest\Domain\Subscriber\Add\SubscriberCreatedDomainEvent;

final class Subscriber
{
    private $id;
    private $email;
    private $domainEvents = [];

    public function __construct(SubscriberId $id, SubscriberEmail $email)
    {
        $this->id    = $id;
        $this->email = $email;
    }

    public static function create(SubscriberId $id, SubscriberEmail $email)
    {
        $subscriber                 = new self($id, $email);
        $subscriber->domainEvents[] = new SubscriberCreatedDomainEvent($id->value(), $email->value());

        return $subscriber;
    }

    public function id() : SubscriberId
    {
        return $this->id;
    }

    public function email() : SubscriberEmail
    {
        return $this->email;
    }

    public function events()
    {
        return $this->domainEvents;
    }
}
