<?php

namespace Core\Auth\Commands\Registration\ByEmail\GenerateActivateToken;

use Core\Auth\Entities\User\Email;
use Core\Auth\Entities\User\User;
use Core\Auth\Exceptions\UserAlreadyActivated;
use Core\Auth\Exceptions\UserNotFoundException;
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
    private $eventDispatcher;

    public function __construct(UserRepository $userRepository, EventDispatcher $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param Command $command
     * @throws Exception
     */
    public function handle(Command $command)
    {
        $email = new Email($command->email);
        $now = new DateTimeImmutable();

        if (!$user = $this->userRepository->findByEmail($email)) {
            throw new UserNotFoundException();
        }

        if (!$user->isUnconfirmed()) {
            throw new UserAlreadyActivated();
        }

        /** @var User $user */
        $user->generateNewActivateToken(ActivateTokenizer::gen($now->modify('+1 hour')));
        $this->userRepository->update($user);

        $this->eventDispatcher->dispatchAll($user->releaseEvents());
    }
}
