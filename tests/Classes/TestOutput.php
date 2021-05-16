<?php

namespace Steevanb\PhpBacktrace\Tests\Classes;

use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestOutput implements OutputInterface
{
    /** @var string */
    protected $output = '';

    public function setFormatter(OutputFormatterInterface $formatter)
    {
    }

    public function getFormatter()
    {
        return new OutputFormatter();
    }

    public function setDecorated($decorated)
    {
    }

    public function isDecorated()
    {
        return false;
    }

    public function setVerbosity($level)
    {
    }

    public function getVerbosity()
    {
        return static::VERBOSITY_QUIET;
    }

    public function isQuiet()
    {
        return true;
    }

    public function isVerbose()
    {
        return false;
    }

    public function isVeryVerbose()
    {
        return false;
    }

    public function isDebug()
    {
        return false;
    }

    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        $this->output .= $messages . "\n";
    }

    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {
        $this->output .= $messages;
    }

    /** @return string */
    public function getOutput()
    {
        return $this->output;
    }
}
