<?php

namespace Core\Auth\Commands\Registration\ByEmail\Confirm;

use Core\Auth\Exceptions\ActivateTokenIsExpired;
use Core\Auth\Exceptions\NotFoundActivateToken;
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
     * @throws Exception
     */
    public function handle(Command $command)
    {
        $token = new ActivateTokenizer($command->token, $now = new DateTimeImmutable());

        if (!$user = $this->userRepository->findByActivateToken($token)) {
            throw new NotFoundActivateToken();
        }

        if ($user->getActivateToken()->isExpired()) {
            throw new ActivateTokenIsExpired();
        }

        $user = $user->confirmByEmail();

        $this->userRepository->update($user);

        $this->dispatcher->dispatchAll($user->releaseEvents());
    }
}
