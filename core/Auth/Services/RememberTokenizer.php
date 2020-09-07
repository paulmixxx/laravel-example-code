<?php

namespace Core\Auth\Services;

use Core\Auth\Entities\User\RememberToken;
use Illuminate\Support\Str;
use Webmozart\Assert\Assert;

class RememberTokenizer implements RememberToken
{
    const LENGTH_RANDOM_STRING = 60;

    /**
     * @var string
     */
    private $value;

    public function __construct($value)
    {
        Assert::length($value, self::LENGTH_RANDOM_STRING);
        $this->value = $value;
    }

    public static function gen()
    {
        return new self(Str::random(self::LENGTH_RANDOM_STRING));
    }

    public function getValue()
    {
        return $this->value;
    }
}
