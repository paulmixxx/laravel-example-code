<?php

namespace Core\Auth\Tests\Unit\Services;

use Core\Auth\Services\ActivateTokenizer;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Str;
use Tests\TestCase;

class ActivateTokenizerTest extends TestCase
{
    public function testCreateSuccess()
    {
        $now = new DateTimeImmutable();
        $token = new ActivateTokenizer(
            $value = Str::random(60),
            $date = $now->modify('+1 hour')
        );

        self::assertEquals($value, $token->getValue());
        self::assertEquals($date, $token->getExpiresTime());
    }

    public function testGenerationSuccess()
    {
        $token = ActivateTokenizer::gen((new DateTimeImmutable())->modify('+1 hour'));

        self::assertInstanceOf(ActivateTokenizer::class, $token);
    }

    /**
     * @dataProvider dataProviderExpireTime
     * @param $data
     * @param $expect
     * @throws Exception
     */
    public function testIsExpireTime($data, $expect)
    {
        $token = ActivateTokenizer::gen((new DateTimeImmutable())->modify($data));

        self::assertEquals($expect, $token->isExpired());
    }

    public function dataProviderExpireTime()
    {
        return [
            ['+1 hour', false],
            ['+0 hour', true],
            ['-1 hour', true],
        ];
    }
}
