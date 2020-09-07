<?php

namespace Core\Auth\Commands\Registration\ByEmail\Request;

use Core\Auth\Entities\User\Email;
use Core\Auth\Entities\User\Name;
use Core\Auth\Entities\User\Password;
use Core\Auth\Entities\User\User;
use Core\Auth\Exceptions\UserAlreadyExists;
use Core\Auth\Repositories\UserRepository;
use Core\Auth\Services\ActivateTokenizer;
use Core\EventDispatcher;
use DateTimeImmutable;
use Exception;

class Handler
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var EventDispatcher
     */
    private $dispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcher $dispatcher)
    {
        $this->userRepository = $userRepository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param Command $command
     * @return void
     * @throws Exception
     */
    public function handle(Command $command)
    {
        $name = new Name(
            $command->firstName,
            $command->lastName,
            $command->middleName
        );
        $email = new Email($command->email);
        $password = new Password($command->password);
        $date = new DateTimeImmutable();
        $expireTime = $date->modify('+1 hour');
        $activateToken = ActivateTokenizer::gen($expireTime);

        if ($this->userRepository->hasByEmail($email)) {
            throw new UserAlreadyExists();
        }

        $user = User::addByEmail(
            $name,
            $email,
            $password,
            $date,
            $activateToken
        );
        $this->userRepository->add($user);

        $this->dispatcher->dispatchAll($user->releaseEvents());
    }
}
