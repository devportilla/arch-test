<?php

namespace Ulatest\Domain\Subscriber\Add;

use DomainException;
use Ulatest\Domain\Subscriber\SubscriberEmail;

final class SubscriberAlreadyExists extends DomainException
{
    private $email;

    public function __construct(SubscriberEmail $email)
    {
        $this->email = $email;

        parent::__construct($this->errorMessage());
    }

    private function errorMessage() : string
    {
        return sprintf('The subscriber <%s> already exists', $this->email->value());
    }
}
