<?php

class DebugBacktrace
{
    /** @var bool|string */
    protected static $removePathPrefix = true;

    /** @var ?string */
    protected static $callerRemoveFile = __FILE__;

    /** @param bool|string $remove */
    public static function setRemovePathPrefix($remove)
    {
        static::$removePathPrefix = $remove;
    }

    /**
     * @param int $offset
     * @param int|null $limit
     * @return array
     */
    public static function getBacktraces($offset = 0, $limit = null)
    {
        if ($limit !== null) {
            $limit += $offset;
        }

        $filteredBacktraces = [];
        foreach (debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, $limit) as $dumpIndex => $backtrace) {
            if ($dumpIndex > $offset) {
                $filteredBacktrace = [
                    'file' => isset($backtrace['file']) ? $backtrace['file'] : null,
                    'line' => isset($backtrace['line']) ? $backtrace['line'] : null,
                ];
                if (isset($backtrace['class'])) {
                    $filteredBacktrace['call'] =
                        $backtrace['class'] . $backtrace['type'] . $backtrace['function'] . '()';
                } elseif (isset($backtrace['function'])) {
                    $filteredBacktrace['call'] = $backtrace['function'] . '()';
                } else {
                    $filteredBacktrace['call'] = '\Closure';
                }

                $filteredBacktraces[] = $filteredBacktrace;
            }
        }

        return array_reverse($filteredBacktraces);
    }

    /** @return array|null */
    public static function getCaller()
    {
        $backtraces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 10);
        $nextIsCaller = false;
        $caller = null;
        foreach ($backtraces as $backtrace) {
            if (
                isset($backtrace['file'])
                && strpos($backtrace['file'], static::$callerRemoveFile) !== false
            ) {
                $nextIsCaller = true;
            } elseif (
                $nextIsCaller
                && (
                    (isset($backtrace['file']) && strpos($backtrace['file'], static::$callerRemoveFile) === false)
                    || isset($backtrace['file']) === false
                )
            ) {
                $caller = $backtrace;
                break;
            }
        }
        if ($nextIsCaller === false && count($backtraces) > 0) {
            $caller = $backtraces[0];
        }

        return $caller;
    }

    /**
     * @param string $code
     * @return string
     */
    protected static function highlightCode($code)
    {
        $highlight = highlight_string('<?php ' . $code, true);
        $highlight = str_replace('>&lt;?php&nbsp;', null, $highlight);

        return $highlight;
    }

    /**
     * @param string $path
     * @return string
     */
    protected static function getFilePath($path)
    {
        $path = realpath($path);

        if (static::$removePathPrefix === false) {
            $return = $path;
        } else {
            // assume that we are in vendor/ dir
            $prefix = (static::$removePathPrefix === true)
                ? realpath(__DIR__ . '/../../../')
                : static::$removePathPrefix;
            $return = (substr($path, 0, strlen($prefix)) === $prefix) ? substr($path, strlen($prefix) + 1) : $path;
        }

        return $return;
    }
}
