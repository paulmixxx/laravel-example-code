<?php

namespace Core\Auth\Tests\Unit\User;

use Core\Auth\Entities\User\Code;
use Core\Auth\Entities\User\Email;
use Core\Auth\Entities\User\Id;
use Core\Auth\Entities\User\Name;
use Core\Auth\Entities\User\Password;
use Core\Auth\Entities\User\Phone;
use Core\Auth\Entities\User\Role;
use Core\Auth\Entities\User\Status;
use Core\Auth\Entities\User\User;
use Core\Auth\Services\ActivateTokenizer;
use Core\Auth\Services\PasswordHasher;
use Core\Auth\Services\RememberTokenizer;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class UserTest extends TestCase
{
    use CreatesApplication;

    public function testCreateSuccess()
    {
        $this->createApplication();

        $user = new User(
            $id = new Id(123),
            $code = Code::gen(),
            $hash = PasswordHasher::getHash($password = new Password("secret_password")),
            $rememberToken = RememberTokenizer::gen(),
            $dateCreate = new DateTimeImmutable(),
            $dateUpdate = new DateTimeImmutable(),
            $status = Status::unconfirmed(),
            $role = Role::user(),
            $name = new Name('Иван', 'Иванов', 'Иванович'),
            $email = new Email('test@mail.com'),
            $phone = new Phone('+79991112233')
        );
        self::assertEquals($id, $user->getId());
        self::assertEquals($code, $user->getCode());
        self::assertEquals($hash, $user->getPasswordHash());
        self::assertEquals($rememberToken, $user->getRememberToken());
        self::assertEquals($dateCreate, $user->getDateCreate());
        self::assertEquals($dateUpdate, $user->getDateUpdate());
        self::assertEquals($status, $user->getStatus());
        self::assertEquals($role, $user->getRole());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($phone, $user->getPhone());
    }

    public function testAddByEmail()
    {
        $user = User::addByEmail(
            $name = new Name('Иван', 'Иванов', 'Иванович'),
            $email = new Email('test@mail.com'),
            $password = new Password("secret_password"),
            $now = new DateTimeImmutable(),
            $token = ActivateTokenizer::gen($now->modify('+1 hour'))
        );
        self::assertInstanceOf(User::class, $user);
        self::assertEquals($now, $user->getDateCreate());
        self::assertEquals($now, $user->getDateUpdate());
        self::assertEquals($name, $user->getName());
        self::assertEquals($email, $user->getEmail());
        self::assertEquals($token, $user->getActivateToken());
    }

    public function testAddByPhone()
    {
        $user = User::addByPhone(
            $phone = new Phone('+79991112233'),
            $password = new Password("secret_password"),
            $now = new DateTimeImmutable()
        );
        self::assertInstanceOf(User::class, $user);
        self::assertEquals($now, $user->getDateCreate());
        self::assertEquals($now, $user->getDateUpdate());
        self::assertEquals($phone, $user->getPhone());
    }
}
