<?php

namespace Core\Auth\Services;

use Core\Auth\Entities\User\ActivateToken;
use DateTimeImmutable;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

class ActivateTokenizer implements ActivateToken
{
    const LENGTH = 60;

    /**
     * @var string
     */
    private $value;
    /**
     * @var DateTimeImmutable
     */
    private $expireTime;

    /**
     * @param string $value
     * @param DateTimeImmutable $expireTime
     */
    public function __construct($value, DateTimeImmutable $expireTime)
    {
        Assert::string($value);
        Assert::length($value, self::LENGTH);

        $this->value = $value;
        $this->expireTime = $expireTime;
    }

    public static function gen(DateTimeImmutable $expireTime)
    {
        $token = Str::random(self::LENGTH);

        return new self($token, $expireTime);
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getExpiresTime()
    {
        return $this->expireTime;
    }

    public function isExpired()
    {
        return $this->getExpiresTime() <= (new DateTimeImmutable());
    }
}
