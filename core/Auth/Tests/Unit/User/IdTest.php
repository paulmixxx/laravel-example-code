<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Id;
use Core\Auth\Exceptions\UserIdException;
use Tests\TestCase;

class IdTest extends TestCase
{
    public function testSuccess()
    {
        $id = new Id();
        self::assertEquals(null, $id->getValue());

        $id = new Id($data = 123);
        self::assertEquals($data, $id->getValue());
    }

    /**
     * @dataProvider dataProviderWrong
     * @param $data
     * @param $expect
     */
    public function testWrong($data, $expect)
    {
        $this->expectException($expect);
        new Id($data);
    }

    public function dataProviderWrong()
    {
        return [
            ['', UserIdException::class],
            ['test', UserIdException::class],
        ];
    }
}
