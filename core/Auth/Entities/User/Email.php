<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserEmailException;
use Exception;
use Webmozart\Assert\Assert;

class Email
{
    private $value;

    public function __construct($value)
    {
        try {
            Assert::notEmpty($value);
            Assert::email($value);
            $this->value = $value;
        } catch (Exception $exception) {
            throw new UserEmailException($exception->getMessage());
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}
