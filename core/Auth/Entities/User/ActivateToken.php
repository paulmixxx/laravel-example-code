<?php

namespace Core\Auth\Entities\User;

use DateTimeImmutable;

interface ActivateToken
{
    /**
     * @param string $value
     * @param DateTimeImmutable $expireTime
     */
    public function __construct($value, DateTimeImmutable $expireTime);
    public static function gen(DateTimeImmutable $expireTime);
    public function getValue();
    public function getExpiresTime();
    public function isExpired();
}
