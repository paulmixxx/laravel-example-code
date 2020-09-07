<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Email;
use Core\Auth\Exceptions\UserEmailException;
use Tests\TestCase;

class EmailTest extends TestCase
{
    public function testSuccess()
    {
        $email = new Email($value = "test@test.com");
        self::assertEquals($value, $email->getValue());
    }

    public function testWrongValue()
    {
        $this->expectException(UserEmailException::class);
        new Email($value = "test");
    }

    public function testEmpty()
    {
        $this->expectException(UserEmailException::class);
        new Email('');
    }
}
