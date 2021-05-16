<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktrace;

use PHPUnit\Framework\TestCase;

class GetCallerTest extends TestCase
{
    public function testGetCaller()
    {
        $caller = \DebugBacktrace::getCaller();

        static::assertInternalType('array', $caller);
        static::assertCount(5, $caller);

        static::assertArrayHasKey('file', $caller);
        static::assertSame(__FILE__, $caller['file']);

        static::assertArrayHasKey('line', $caller);
        static::assertSame(11, $caller['line']);

        static::assertArrayHasKey('function', $caller);
        static::assertSame('getCaller', $caller['function']);

        static::assertArrayHasKey('class', $caller);
        static::assertSame('DebugBacktrace', $caller['class']);

        static::assertArrayHasKey('type', $caller);
        static::assertSame('::', $caller['type']);
    }
}
