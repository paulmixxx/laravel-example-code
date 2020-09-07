<?php

namespace Core\Auth\Exceptions;

use DomainException;

class UserNotFoundException extends DomainException
{
    protected $message = 'User not found';
}
