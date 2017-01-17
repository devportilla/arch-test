<?php

namespace Ulatest\Domain\Subscriber;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;

final class SubscriberEmail
{
    public function __construct(string $value)
    {
        self::assertValidAddress($value);

        $this->value = $value;
    }

    public function value() : string
    {
        return $this->value;
    }

    public function __toString()
    {
        return (string)$this->value();
    }

    private static function assertValidAddress($address)
    {
        $validator = new EmailValidator();

        if (!$validator->isValid($address, new RFCValidation())) {
            throw new \InvalidArgumentException('Email address is not valid');
        }
    }
}
