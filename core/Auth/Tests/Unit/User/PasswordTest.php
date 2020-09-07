<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Password;
use Core\Auth\Exceptions\UserPasswordException;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    public function dataProviderSuccess()
    {
        return [
            ["goodpass", "goodpass"],
            ["123123", "123123"],
            [123123, 123123],
            ["@!01-A123l;kx/,", "@!01-A123l;kx/,"],
        ];
    }

    public function dataProviderWrong()
    {
        return [
            [null, UserPasswordException::class],
            ["", UserPasswordException::class],
            ["1", UserPasswordException::class],
            ["12", UserPasswordException::class],
            ["123", UserPasswordException::class],
            ["1234", UserPasswordException::class],
            ["12345", UserPasswordException::class],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testSuccess($data, $expect)
    {
        $password = new Password($data);
        self::assertEquals($expect, $password->getValue());
    }

    /**
     * @dataProvider dataProviderWrong
     * @param $data
     * @param $expect
     */
    public function testWrong($data, $expect)
    {
        $this->expectException($expect);
        new Password($data);
    }
}
