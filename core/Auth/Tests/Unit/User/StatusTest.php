<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Status;
use Tests\TestCase;

class StatusTest extends TestCase
{
    public function dataProviderSuccess()
    {
        return [
            [Status::unconfirmed(), 1],
            [Status::active(), 2],
            [Status::inactive(), 3],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     * @param Status $data
     * @param $expect
     */
    public function testSuccess($data, $expect)
    {
        self::assertEquals($expect, $data->getValue());
    }
}
