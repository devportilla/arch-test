<?php

namespace Ulatest\Domain\Subscriber\Add;

final class SubscriberCreatedDomainEvent
{
    private $subscriberId;
    private $subscriberEmail;

    public function __construct(string $id, string $email)
    {
        $this->subscriberId    = $id;
        $this->subscriberEmail = $email;
    }

    public function id() : string
    {
        return $this->subscriberId;
    }

    public function email() : string
    {
        return $this->subscriberEmail;
    }
}
