<?php

namespace Ulatest\Test\Stub;

use Faker\Factory;
use Ulatest\Domain\Subscriber\SubscriberId;

final class SubscriberIdStub
{
    public static function create($subscriberId)
    {
        return new SubscriberId($subscriberId);
    }

    public static function random()
    {
        return self::create(Factory::create()->numberBetween(1, 1000));
    }
}
