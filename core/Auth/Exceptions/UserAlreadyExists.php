<?php

namespace Core\Auth\Exceptions;

use DomainException;

class UserAlreadyExists extends DomainException
{
    protected $message = 'User already exists';
}
