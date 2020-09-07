<?php

namespace Core\Auth\Commands\Registration\ByEmail\Request;

class Command
{
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var string
     */
    public $middleName;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
}
