<?php

use \Symfony\Component\Console\Output\OutputInterface;
use \Symfony\Component\Console\Helper\Table;

class DebugBacktraceConsole extends \DebugBacktrace
{
    /** @var ?string */
    protected static $callerRemoveFile = __FILE__;
    
    /**
     * @param OutputInterface $output
     * @param int $offset
     * @param int|null $limit
     */
    public static function dump(OutputInterface $output, $offset = 0, $limit = null)
    {
        static::writeCaller($output);

        $table = new Table($output);
        $table->setHeaders(['#', 'file', 'line', 'call']);
        foreach (static::getBacktraces($offset, $limit) as $index => $backtrace) {
            static::writeBacktraceDump($table, $backtrace, $index);
        }
        $table->render();
    }

    /**
     * @param OutputInterface $output
     * @param int $offset
     * @param int|null $limit
     */
    public static function eDump(OutputInterface $output, $offset = 0, $limit = null)
    {
        static::dump($output, $offset + 1, $limit);
        exit();
    }

    /**
     * @param Table $table
     * @param array $backtrace
     * @param int $index
     */
    protected static function writeBacktraceDump(Table $table, array $backtrace, $index)
    {
        $table->addRow([
            $index + 1,
            static::getFilePath($backtrace['file']),
            $backtrace['line'],
            $backtrace['call']
        ]);
    }

    /** @param OutputInterface $output */
    protected static function writeCaller(OutputInterface $output)
    {
        $caller = static::getCaller();

        if (is_array($caller)) {
            $output->writeln(
                'From '
                . '<info>' . $caller['file'] . '</info>'
                . '<comment>#' . $caller['line'] . '</comment>'
            );
        } else {
            $output->writeln('<error>Unkonw caller</error>');
        }
    }
}
