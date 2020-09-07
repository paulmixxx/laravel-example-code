<?php

namespace Core\Auth\Services;

use Core\Auth\Entities\User\Password;
use Core\Auth\Entities\User\PasswordHash;
use Illuminate\Support\Facades\Hash;

class PasswordHasher implements PasswordHash
{
    /**
     * @var string
     */
    private $hash;

    public function __construct($hash)
    {
        $this->hash = $hash;
    }

    public static function getHash(Password $password)
    {
        $hash = Hash::make($password->getValue());

        return new self($hash);
    }

    public function validate(Password $password)
    {
        return Hash::check($password->getValue(), $this->getValue());
    }

    public function getValue()
    {
        return $this->hash;
    }
}
