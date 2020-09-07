<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function dataProviderSuccess()
    {
        return [
            [Role::admin(), 1],
            [Role::editor(), 2],
            [Role::moderator(), 3],
            [Role::user(), 4],
        ];
    }

    /**
     * @dataProvider dataProviderSuccess
     * @param Role $data
     * @param $expect
     */
    public function testSuccess($data, $expect)
    {
        self::assertEquals($expect, $data->getValue());
    }
}
