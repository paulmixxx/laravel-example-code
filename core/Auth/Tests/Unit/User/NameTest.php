<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Name;
use Core\Auth\Exceptions\UserNameException;
use Tests\TestCase;

class NameTest extends TestCase
{
    public function nameSuccessDataProvider()
    {
        return [
            ["Иван", "Иван"],
            ["    Иван    ", "Иван"],
            ["Ivan", "Ivan"],
            ["    Ivan    ", "Ivan"],
        ];
    }

    public function nameValueExtendedSuccess()
    {
        $parent = $this->nameSuccessDataProvider();
        $new = [
            [null, null],
        ];

        return array_merge($new, $parent);
    }

    public function nameWrongDataProvider()
    {
        return [
            ["", UserNameException::class],
            ["ф", UserNameException::class],
            ["q", UserNameException::class],
            ["ййййййййййййййййййййййййййййййййййййййй", UserNameException::class],
            ["qqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqqq", UserNameException::class],
            ["Иван123", UserNameException::class],
            ["Ivan123", UserNameException::class],
            ["Иван'", UserNameException::class],
            ["Ivan'", UserNameException::class],
            ["Иван Иван", UserNameException::class],
            ["Ivan Ivan", UserNameException::class],
            ["test@test.ru", UserNameException::class],
            [123123, UserNameException::class],
            ["123123", UserNameException::class],
            ["/>.#@%$#$%@#~!", UserNameException::class],
        ];
    }

    /**
     * @dataProvider nameSuccessDataProvider
     * @param $firstName
     * @param $expect
     */
    public function testGetFirstNameSuccess($firstName, $expect)
    {
        $name = new Name($firstName);
        self::assertEquals($expect, $name->getFirst());
    }

    /**
     * @dataProvider nameWrongDataProvider
     * @param $firstName
     * @param $expect
     */
    public function testGetFirstNameWrong($firstName, $expect)
    {
        $this->expectException($expect);
        new Name($firstName);
    }

    /**
     * @dataProvider nameValueExtendedSuccess
     * @param $lastName
     * @param $expect
     */
    public function testGetLastName($lastName, $expect)
    {
        $name = new Name("Иван", $lastName);
        self::assertEquals($expect, $name->getLast());
    }

    /**
     * @dataProvider nameWrongDataProvider
     * @param $lastName
     * @param $expect
     */
    public function testGetLastNameWrong($lastName, $expect)
    {
        $this->expectException($expect);
        new Name("Иван", $lastName);
    }

    /**
     * @dataProvider nameValueExtendedSuccess
     * @param $middle
     * @param $expect
     */
    public function testGetMiddleName($middle, $expect)
    {
        $name = new Name("Иван", "Иванов", $middle);
        self::assertEquals($expect, $name->getMiddle());
    }

    /**
     * @dataProvider nameWrongDataProvider
     * @param $middle
     * @param $expect
     */
    public function testGetMiddleNameWrong($middle, $expect)
    {
        $this->expectException($expect);
        new Name("Иван", "Иванов", $middle);
    }

    public function testGetFullName()
    {
        $name = new Name($first = "Иван", $last = "Иванов", $middle = "Иванович");
        $fullName = $first . " " . $middle . " " . $last;
        self::assertEquals($fullName, $name->getFullName());
    }

    public function testGetShortName()
    {
        $name = new Name($first = "Иван", $last = "Иванов", $middle = "Иванович");
        $shortName = $first . " " . $last;
        self::assertEquals($shortName, $name->getShortName());
    }
}
