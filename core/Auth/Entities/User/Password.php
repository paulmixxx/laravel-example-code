<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserPasswordException;
use Exception;
use Webmozart\Assert\Assert;

class Password
{
    private $value;

    public function __construct($value)
    {
        try {
            Assert::notNull($value);
            Assert::notEmpty($value);
            Assert::minLength($value, 6);

            $this->value = $value;
        } catch (Exception $exception) {
            throw new UserPasswordException($exception->getMessage());
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}
