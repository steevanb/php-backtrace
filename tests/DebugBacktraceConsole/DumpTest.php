<?php

namespace Steevanb\PhpBacktrace\Tests\DebugBacktraceConsole;

use PHPUnit\Framework\TestCase;
use Steevanb\PhpBacktrace\Tests\Classes\TestOutput;

class DumpTest extends TestCase
{
    protected static function assertOutput(TestOutput $output)
    {
        static::assertInternalType('string', $output->getOutput());
        static::assertInternalType('int', strpos($output->getOutput(), '|<info> line </info>|'));
    }

    public function testTestOutput()
    {
        $output = new TestOutput();
        \DebugBacktraceConsole::dump($output);

        static::assertOutput($output);
    }

    public function testOffset1()
    {
        $output = new TestOutput();
        \DebugBacktraceConsole::dump($output, 1);

        static::assertOutput($output);
    }

    public function testLimit2()
    {
        $output = new TestOutput();
        \DebugBacktraceConsole::dump($output, 0, 2);

        static::assertOutput($output);
    }

    public function testNoGivenOutput()
    {
        // I don't know if we can get ConsoleOutput writed lines as it's not writed with echo so ob_x does no work?
        \DebugBacktraceConsole::dump();
    }
}
