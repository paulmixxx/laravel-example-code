<?php

namespace Core\Auth\Tests\Unit\Services;

use Core\Auth\Services\RememberTokenizer;
use PHPUnit\Framework\TestCase;

class RememberTokenizerTest extends TestCase
{
    public function testSuccess()
    {
        $token = RememberTokenizer::gen();
        self::assertEquals(RememberTokenizer::LENGTH_RANDOM_STRING, mb_strlen($token->getValue()));
    }
}
