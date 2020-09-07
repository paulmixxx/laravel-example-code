<?php

namespace Tests\Feature\Commands\Registration\ByEmail;

use Core\Auth\Exceptions\UserAlreadyExists;
use Event;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Core\Auth\Events\UserRegisteredByEmailEvent;
use Core\Auth\Commands\Registration\ByEmail\Request;
use Core\Auth\Repositories\UserRepository;
use Core\EventDispatcher;

class RequestTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * @dataProvider dataProviderUserRegisterSuccess
     * @param $data
     * @param $expect
     * @throws Exception
     */
    public function testUserRegisterSuccess($data, $expect)
    {
        Event::fake();

        $this->expectsEvents($expect);

        $this->createUser($data);

        $this->assertDatabaseHas('users', [
            'email' => $data['email']
        ]);
    }

    /**
     * @throws Exception
     */
    public function testUserAlreadyExists()
    {
        Event::fake();

        $this->expectException(UserAlreadyExists::class);

        $user = [
            'email' => 'new.email@mail.com',
            'password' => 'secret_password',
            'firstName' => 'Ivan',
        ];

        $this->createUser($user);
        $this->createUser($user);
    }

    /**
     * @param array $data
     * @throws Exception
     */
    public function createUser($data)
    {
        $dto = new Request\Command();
        $userRepository = new UserRepository();
        $eventDispatcher = new EventDispatcher();

        $handler = new Request\Handler(
            $userRepository,
            $eventDispatcher
        );

        foreach ($data as $k => $v) {
            $dto->$k = $v;
        }

        $handler->handle($dto);
    }

    public function dataProviderUserRegisterSuccess()
    {
        return [
            [
                [
                    'email' => 'new.email@mail.com',
                    'password' => 'secret_password',
                    'firstName' => 'Ivan',
                ],
                UserRegisteredByEmailEvent::class
            ],
            [
                [
                    'email' => 'new.email@mail.com',
                    'password' => 'secret_password',
                    'firstName' => 'Ivan',
                    'lastName' => 'Ivanov',
                ],
                UserRegisteredByEmailEvent::class
            ],
            [
                [
                    'email' => 'new.email@mail.com',
                    'password' => 'secret_password',
                    'firstName' => 'Ivan',
                    'lastName' => 'Ivanov',
                    'middleName' => 'Ivanovich',
                ],
                UserRegisteredByEmailEvent::class
            ],
        ];
    }
}
