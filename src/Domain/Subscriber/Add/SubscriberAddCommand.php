<?php

namespace Ulatest\Domain\Subscriber\Add;

final class SubscriberAddCommand
{
    private $id;
    private $email;

    public function __construct(int $id, string $email)
    {
        $this->id    = $id;
        $this->email = $email;
    }

    public function id() : int
    {
        return $this->id;
    }

    public function email() : string
    {
        return $this->email;
    }
}
