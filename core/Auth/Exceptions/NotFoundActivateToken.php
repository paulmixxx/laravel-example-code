<?php

namespace Core\Auth\Exceptions;

use DomainException;

class NotFoundActivateToken extends DomainException
{
    protected $message = 'Not found activate token';
}
