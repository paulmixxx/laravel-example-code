<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Code;
use Core\Auth\Exceptions\UserCodeException;
use InvalidArgumentException;
use Tests\TestCase;

class CodeTest extends TestCase
{
    /**
     * @throws UserCodeException
     */
    public function testSuccess()
    {
        $code = new Code($data = '67be95c2-cc48-421f-924d-d3afb0b14a7b');
        self::assertEquals($data, $code->getValue());
    }

    public function testGeneration()
    {
        self::assertInstanceOf(Code::class, Code::gen());
    }

    /**
     * @dataProvider dataProviderWrong
     * @param $data
     * @throws UserCodeException
     */
    public function testWrong($data, $expect)
    {
        $this->expectException($expect);
        new Code($data);
    }

    public function dataProviderWrong()
    {
        return [
            [null, UserCodeException::class],
            ['', UserCodeException::class],
            ['test', UserCodeException::class],
            ['123132', UserCodeException::class],
            [1321231, UserCodeException::class],
        ];
    }
}
