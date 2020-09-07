<?php

namespace Core\Auth\Exceptions;

use DomainException;

class UserAlreadyActivated extends DomainException
{
    protected $message = 'User already activated';
}
