<?php

namespace Core\Auth\Entities\User;

interface PasswordHash
{
    public function getValue();
    public static function getHash(Password $password);
    public function validate(Password $password);
}
