<?php

namespace Core\Auth\Entities\User;

use Core\Auth\Exceptions\UserNameException;
use Exception;
use Webmozart\Assert\Assert;

class Name
{
    /**
     * @var string
     */
    private $first;
    /**
     * @var string|null
     */
    private $last;
    /**
     * @var string|null
     */
    private $middle;

    public function __construct($first, $last = null, $middle = null)
    {
        try {
            $first = trim($first);
            $last = $last ? trim($last) : $last;
            $middle = $middle ? trim($middle) : $middle;

            Assert::notContains($first, ' ');
            Assert::lengthBetween($first, 2, 20);
            Assert::regex($first, "/^[a-zа-яёЁ]+$/ui");

            Assert::nullOrNotContains($last, ' ');
            Assert::nullOrLengthBetween($last, 2, 20);
            Assert::nullOrRegex($last, "/^[a-zа-яёЁ]+$/ui");

            Assert::nullOrNotContains($middle, ' ');
            Assert::nullOrLengthBetween($middle, 2, 20);
            Assert::nullOrRegex($middle, "/^[a-zа-яёЁ]+$/ui");

            $this->first = $first;
            $this->last = $last;
            $this->middle = $middle;
        } catch (Exception $exception) {
            throw new UserNameException($exception->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getFirst()
    {
        return $this->first;
    }

    /**
     * @return string|null
     */
    public function getLast()
    {
        return $this->last;
    }

    /**
     * @return string|null
     */
    public function getMiddle()
    {
        return $this->middle;
    }

    /**
     * @param string $order
     * @return string
     */
    public function getFullName($order = "first")
    {
        switch ($order) {
            case "last":
                $arr = [
                    $this->getLast(),
                    $this->getMiddle(),
                    $this->getFirst(),
                ];
                break;
            default:
                $arr = [
                    $this->getFirst(),
                    $this->getMiddle(),
                    $this->getLast()
                ];
        }

        return $this->glueStr($arr, " ");
    }

    /**
     * @param string $order
     * @return string
     */
    public function getShortName($order = "first")
    {
        switch ($order) {
            case "last":
                $arr = [
                    $this->getLast(),
                    $this->getFirst(),
                ];
                break;
            default:
                $arr = [
                    $this->getFirst(),
                    $this->getLast()
                ];
        }

        return $this->glueStr($arr, " ");
    }

    /**
     * @param array $arr
     * @param string $glue
     * @return string
     */
    private function glueStr($arr, $glue)
    {
        $arr = array_filter(
            $arr,
            function ($value) {
                return !empty($value);
            }
        );
        return implode($glue, $arr);
    }
}
