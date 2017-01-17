<?php

namespace Ulatest\Test\Stub;

use Ulatest\Domain\Subscriber\Add\SubscriberAddCommand;
use Ulatest\Domain\Subscriber\SubscriberEmail;
use Ulatest\Domain\Subscriber\SubscriberId;

final class SubscriberAddCommandStub
{
    public static function create(SubscriberId $subscriberId, SubscriberEmail $subscriberEmail)
    {
        return new SubscriberAddCommand($subscriberId->value(), $subscriberEmail->value());
    }
}
