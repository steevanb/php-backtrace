<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktraceHtml;

use PHPUnit\Framework\TestCase;

class GetDumpTest extends TestCase
{
    public function testGetDump()
    {
        $dump = \DebugBacktraceHtml::getDump(\DebugBacktrace::getBacktraces());

        static::assertInternalType('string', $dump);
        static::assertInternalType('int', strpos($dump, '<div class="steevanb-backtrace-container">'));
    }
}
