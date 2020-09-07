<?php

namespace Core\Auth\Events;

use Core\Auth\Entities\User\Email;
use Core\Auth\Entities\User\Name;
use Core\Auth\Entities\User\User;
use DateTimeImmutable;

class UserRegisteredByPhoneEvent
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getEntity()
    {
        return $this->user;
    }
}
