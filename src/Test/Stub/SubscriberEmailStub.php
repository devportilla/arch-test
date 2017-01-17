<?php

namespace Ulatest\Test\Stub;

use Faker\Factory;
use Ulatest\Domain\Subscriber\SubscriberEmail;

final class SubscriberEmailStub
{
    public static function create($subscriberEmail)
    {
        return new SubscriberEmail($subscriberEmail);
    }

    public static function random()
    {
        return self::create(Factory::create()->email);
    }
}
