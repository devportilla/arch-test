<?php

namespace Ulatest\Test\Stub;

use Ulatest\Domain\Subscriber\Subscriber;
use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberId;

final class SubscriberStub
{
    public static function create(SubscriberId $subscriberId, SubscriberEmail $subscriberEmail)
    {
        return new Subscriber($subscriberId, $subscriberEmail);
    }

    public static function random()
    {
        return self::create(SubscriberIdStub::random(), SubscriberEmailStub::random());
    }
}
