<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserPhoneException;
use Exception;
use Webmozart\Assert\Assert;

class Phone
{
    private $country;
    private $code;
    private $number;

    public function __construct($value)
    {
        try {
            $pattern = "/^\+(?<country>\d{1,3})(?<code>\d{3})(?<number>\d{7})$/iu";
            Assert::regex($value, $pattern);

            preg_match($pattern, $value, $match);

            $this->country = $match["country"];
            $this->code = $match["code"];
            $this->number = $match["number"];
        } catch (Exception $exception) {
            throw new UserPhoneException($exception->getMessage());
        }
    }

    public function getCountryCode()
    {
        return $this->country;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getValue()
    {
        return "+" . $this->getCountryCode() . $this->getCode() . $this->getNumber();
    }
}
