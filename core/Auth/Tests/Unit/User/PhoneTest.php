<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Phone;
use Core\Auth\Exceptions\UserPhoneException;
use Tests\TestCase;

class PhoneTest extends TestCase
{
    public function dataProviderSuccess()
    {
        return [
            ["+79991111111", "+79991111111"],
            ["+3809991111111", "+3809991111111"],
            ["+19991111111", "+19991111111"],
        ];
    }

    public function dataProviderWrong()
    {
        return [
            ["+7 (999) 111-11-11", UserPhoneException::class],
            ["+380 (999) 111-11-11", UserPhoneException::class],
            ["+1 (999) 111-11-11", UserPhoneException::class],
            ["89991112223344", UserPhoneException::class],
            ["8 (999) 111-22-33", UserPhoneException::class],
            ["7 999 111-22-33", UserPhoneException::class],
            ["+7 999 111-22-33", UserPhoneException::class],
            ["+7 9991112233", UserPhoneException::class],
            ["+380 9991112233", UserPhoneException::class],
            ["+380 (999) 1112233", UserPhoneException::class],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     */
    public function testSuccess($data, $expect)
    {
        $phone = new Phone($data);
        self::assertEquals($expect, $phone->getValue());
    }

    /**
     * @dataProvider dataProviderWrong
     * @param $data
     * @param $expect
     */
    public function testWrong($data, $expect)
    {
        $this->expectException($expect);
        new Phone($data);
    }
}
