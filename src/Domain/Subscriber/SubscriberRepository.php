<?php

namespace Ulatest\Domain\Subscriber;

interface SubscriberRepository
{
    /**
     * @param Subscriber $subscriber
     *
     * @return void
     */
    public function save(Subscriber $subscriber);

    /**
     * @param SubscriberEmail $email
     *
     * @return Subscriber
     */
    public function searchByEmail(SubscriberEmail $email);
}
