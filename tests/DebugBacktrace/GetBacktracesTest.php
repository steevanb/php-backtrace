<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktrace;

use PHPUnit\Framework\TestCase;

class GetBacktracesTest extends TestCase
{
    public function testWithoutArguments()
    {
        $backtraces = \DebugBacktrace::getBacktraces();

        static::assertInternalType('array', $backtraces);
        static::assertCount(
            version_compare(PHP_VERSION, '8.0.0', '>=') ? 10 : 11,
            $backtraces
        );
    }

    public function testOffset1()
    {
        $backtraces = \DebugBacktrace::getBacktraces(1);

        static::assertInternalType('array', $backtraces);
        static::assertCount(
            version_compare(PHP_VERSION, '8.0.0', '>=') ? 9 : 10,
            $backtraces
        );
    }

    public function testLimit2()
    {
        // Weird behavior: limit should be 2 to have last backtrace in debug_backtrace()
        $backtraces = \DebugBacktrace::getBacktraces(0, 2);

        static::assertInternalType('array', $backtraces);
        static::assertCount(1, $backtraces);
    }
}
