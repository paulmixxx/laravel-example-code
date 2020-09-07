<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserCodeException;
use Exception;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

class Code
{
    private $value;

    public function __construct($value)
    {
        try {
            Assert::uuid($value);
            $this->value = $value;
        } catch (Exception $exception) {
            throw new UserCodeException($exception->getMessage());
        }
    }

    public static function gen()
    {
        return new self(Uuid::uuid4()->toString());
    }

    public function getValue()
    {
        return $this->value;
    }
}
