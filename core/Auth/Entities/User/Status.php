<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserStatusException;
use Exception;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Status
{
    const UNCONFIRMED = 1;
    const ACTIVE = 2;
    const INACTIVE = 3;

    private $available = [
        self::UNCONFIRMED,
        self::ACTIVE,
        self::INACTIVE,
    ];

    private $value;

    public function __construct($value)
    {
        try {
            if (!$this->has($value)) {
                throw new InvalidArgumentException();
            }

            $value = (int) $value;

            Assert::integer($value);

            $this->value = $value;
        } catch (Exception $exception) {
            throw new UserStatusException($exception->getMessage());
        }
    }

    public static function unconfirmed()
    {
        return new self(self::UNCONFIRMED);
    }

    public static function active()
    {
        return new self(self::ACTIVE);
    }

    public static function inactive()
    {
        return new self(self::INACTIVE);
    }

    public function getValue()
    {
        return $this->value;
    }

    private function has($status)
    {
        return in_array($status, $this->available);
    }

    public function isUnconfirmed()
    {
        return self::UNCONFIRMED === $this->getValue();
    }
}
