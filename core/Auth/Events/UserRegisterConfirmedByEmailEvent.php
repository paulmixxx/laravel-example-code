<?php

namespace Core\Auth\Events;

use Core\Auth\Entities\User\User;

class UserRegisterConfirmedByEmailEvent
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
