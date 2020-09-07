<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserIdException;
use Exception;
use Webmozart\Assert\Assert;

class Id
{
    /**
     * @var int
     */
    private $value;

    public function __construct($value = null)
    {
        try {
            Assert::nullOrInteger($value);
            $this->value = $value;
        } catch (Exception $exception) {
            throw new UserIdException($exception->getMessage());
        }
    }

    public function getValue()
    {
        return $this->value;
    }
}
