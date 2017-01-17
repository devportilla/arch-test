<?php

namespace Ulatest\Domain\Subscriber;

final class SubscriberId
{
    private $id;

    public function __construct(int $id)
    {
        \Assert\that($id)->integer();
        $this->id = $id;
    }

    public function value() : int
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->value();
    }
}
