<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserRoleException;
use Exception;
use InvalidArgumentException;
use Webmozart\Assert\Assert;

class Role
{
    const ADMIN = 1;
    const EDITOR = 2;
    const MODERATOR = 3;
    const USER = 4;

    private $available = [
        self::ADMIN,
        self::EDITOR,
        self::MODERATOR,
        self::USER,
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
            throw new UserRoleException($exception->getMessage());
        }
    }

    public static function admin()
    {
        return new self(self::ADMIN);
    }

    public static function editor()
    {
        return new self(self::EDITOR);
    }

    public static function moderator()
    {
        return new self(self::MODERATOR);
    }

    public static function user()
    {
        return new self(self::USER);
    }

    public function getValue()
    {
        return $this->value;
    }

    private function has($status)
    {
        return in_array($status, $this->available);
    }
}
