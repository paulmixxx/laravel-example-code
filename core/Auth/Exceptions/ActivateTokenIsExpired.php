<?php

namespace Core\Auth\Exceptions;

use Exception;

class ActivateTokenIsExpired extends Exception
{
    protected $message = 'Activate token is expired';
}
