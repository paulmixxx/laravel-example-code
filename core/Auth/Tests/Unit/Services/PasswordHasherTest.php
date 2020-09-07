<?php

namespace Core\Auth\Tests\Unit\Services;

use Core\Auth\Entities\User\Password;
use Core\Auth\Services\PasswordHasher;
use Tests\TestCase;

class PasswordHasherTest extends TestCase
{
    public function testSuccess()
    {
        $password = new Password("secret_password");
        $passwordHasher = PasswordHasher::getHash($password);
        self::assertTrue($passwordHasher->validate($password));
    }
}
