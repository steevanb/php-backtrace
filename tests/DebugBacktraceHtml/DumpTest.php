<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktraceHtml;

use PHPUnit\Framework\TestCase;

class DumpTest extends TestCase
{
    protected static function assertDump($dump)
    {
        static::assertInternalType('string', $dump);
        static::assertInternalType('int', strpos($dump, '<div class="steevanb-backtrace-container">'));
    }

    public function testDump()
    {
        ob_start();
        \DebugBacktraceHtml::dump();
        $dump = ob_get_contents();

        static::assertDump($dump);
    }

    public function testOffset1()
    {
        ob_start();
        \DebugBacktraceHtml::dump(1);
        $dump = ob_get_contents();

        static::assertDump($dump);
    }

    public function testLimit2()
    {
        ob_start();
        \DebugBacktraceHtml::dump(0, 2);
        $dump = ob_get_contents();

        static::assertDump($dump);
    }
}
