<?php

namespace Ulatest\Domain\Subscriber;

final class SubscriberIdGenerator
{
    public function id()
    {
        return random_int(1, 1000);
    }
}
