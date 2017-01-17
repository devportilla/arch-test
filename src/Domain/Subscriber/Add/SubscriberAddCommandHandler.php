<?php

namespace Ulatest\Domain\Subscriber\Add;

use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberId;

final class SubscriberAddCommandHandler
{
    /** @var SubscriberAdder */
    private $adder;

    public function __construct(SubscriberAdder $adder)
    {
        $this->adder = $adder;
    }

    public function __invoke(SubscriberAddCommand $command)
    {
        $email = new SubscriberEmail($command->email());
        $id    = new SubscriberId($command->id());

        $this->adder->__invoke($id, $email);
    }
}
