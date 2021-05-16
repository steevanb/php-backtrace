<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktrace;

use PHPUnit\Framework\TestCase;

class SetRemovePathPrefixTest extends TestCase
{
    public function testWithTrue()
    {
        \DebugBacktrace::setRemovePathPrefix(true);

        static::addToAssertionCount(1);
    }

    public function testWithfalse()
    {
        \DebugBacktrace::setRemovePathPrefix(true);

        static::addToAssertionCount(1);
    }

    public function testWithPrefix()
    {
        \DebugBacktrace::setRemovePathPrefix('/foo');

        static::addToAssertionCount(1);
    }
}
