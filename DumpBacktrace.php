<?php

class DumpBacktrace
{
    /** @var bool|string */
    protected static $removePathPrefix = true;

    /**
     * @param bool|string $remove
     */
    public static function setRemovePathPrefix($remove)
    {
        static::$removePathPrefix = $remove;
    }

    /**
     * @param int $offset
     * @param int|null $limit
     */
    public static function dump($offset = 0, $limit = null)
    {
        echo static::getBacktracesDump(static::getBacktraces($offset + 1, $limit));
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
                    $filteredBacktrace['call'] = '(Unknow call)';
                }

                $filteredBacktraces[] = $filteredBacktrace;
            }
        }

        return $filteredBacktraces;
    }

    /**
     * @return array|null
     */
    protected static function getCaller()
    {
        $backtraces = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 7);
        $nextIsCaller = false;
        $caller = null;
        foreach ($backtraces as $backtrace) {
            if (
                isset($backtrace['file'])
                && strpos($backtrace['file'], __FILE__) !== false
            ) {
                $nextIsCaller = true;
            } elseif (
                $nextIsCaller
                && (
                    (isset($backtrace['file']) && strpos($backtrace['file'], __FILE__) === false)
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
     * @param array $backtraces
     * @return string
     */
    protected static function getBacktracesDump(array $backtraces)
    {
        $return = static::getStylesDump();
        $return .= static::getJavascriptDump();

        $return .= '<div class="steevanb-backtrace-container">';
        $return .= static::getCallerDump();

        $return .= '
            <table class="table-backtrace">
                <tr>
                    <th>#</th>
                    <th>File::Line</th>
                    <th>Call</th>
                </tr>
        ';
        $previewPrefix = uniqid('steevanb_backtrace_preview');
        foreach ($backtraces as $index => $backtrace) {
            $return .= static::getBacktraceDump($backtrace, $index, $previewPrefix);
        }
        $return .= '</table>';

        $return .= '</div>';

        return $return;
    }

    /**
     * @return string
     */
    protected static function getStylesDump()
    {
        return '
            <style type="text/css">
                .steevanb-backtrace-container {
                    padding: 5px;
                    border: solid 2px #9e9e9e;
                    background-color: #F5F5F5;
                    cursor: default;
                    font-family: monospace;
                }
                .steevanb-backtrace-container table {
                    border-collapse: collapse;
                }
                .steevanb-backtrace-container table.table-backtrace tr.dark {
                    background-color: #e5e5e5;
                }
                .steevanb-backtrace-container table.table-backtrace td {
                    padding: 2px !important;
                }
                .steevanb-backtrace-container table.table-backtrace td a,
                .steevanb-backtrace-container table.table-backtrace td a:hover,
                .steevanb-backtrace-container table.table-backtrace td a:visited
                {
                    color: #4e7ca9 !important;
                    text-decoration: none !important;
                    cursor: pointer !important;
                }
                .steevanb-backtrace-container table.table-backtrace td a:hover {
                    text-decoration: underline !important;
                }
            </style>';
    }

    /**
     * @return string
     */
    protected static function getJavascriptDump()
    {
        return '
            <script type="text/javascript">
                function steevanb_dev_showCodePreview(id)
                {
                    var element = document.getElementById(id);
                    element.style.display = (element.style.display === "none") ? "" : "none";
                }
            </script>';
    }

    /**
     * @param array $backtrace
     * @param int $index
     * @param string $previewPrefix
     * @return string
     */
    protected static function getBacktraceDump(array $backtrace, $index, $previewPrefix)
    {
        if (isset($backtrace['file'])) {
            $file = basename($backtrace['file']);
            $filePath = $backtrace['file'];
            $fileFound = true;
        } else {
            $filePath = null;
            $fileFound = false;
        }

        if (isset($backtrace['line'])) {
            $line = $backtrace['line'];
            $lineFound = true;
        } else {
            $lineFound = false;
        }

        if ($fileFound === false && $lineFound === false) {
            $fileLineHtml = '\Closure';
        } else {
            $previewId = $previewPrefix . '_' . $index;
            $codePreview = static::getCodePreview($filePath, $line);
            $fileLineHtml = '
                <a
                    title="' . static::getFilePath($filePath) . '"
                    onclick="steevanb_dev_showCodePreview(\'' . $previewId . '\')"
                >
                    ' . $file . '::' . $line . '
                </a>
            ';
        }

        $html = '
            <tr' . ($index % 2 ? null : ' class="dark"') . '>
                <td>' . $index . '</td>
                <td>' . $fileLineHtml . '</td>
                <td>' . $backtrace['call'] . '</td>
            </tr>
        ';
        if ($fileFound && $lineFound) {
            $html .= '
                <tr' . ($index % 2 ? null : ' class="dark"') . ' id="' . $previewId . '" style="display: none">
                    <td colspan="3"><pre>' . $codePreview . '</pre></td>
                </tr>
            ';
        }

        return $html;
    }

    /**
     * @return string
     */
    protected static function getCallerDump()
    {
        $caller = static::getCaller();

        $return = '<div style="padding: 5px; background-color: #78a1c9; color: white; font-weight: bold">';
        $header = null;
        if (is_array($caller)) {
            $header .= isset($caller['file']) ? static::getFilePath($caller['file']) : '(Unknow file)';
            $header .= isset($caller['line']) ? '::' . $caller['line'] : '::(Unknow line)';
        } else {
            $header= 'Unkonw caller';
        }
        $return .= $header;
        $return .= '</div>';

        return $return;
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
     * @param string $file
     * @param int $line
     * @return string
     */
    protected static function getCodePreview($file, $line)
    {
        $preview = [];
        $lineMin = $line - 6;
        $lineMax = $line + 4;
        foreach (file($file) as $index => $codeLine) {
            if ($index >= $lineMin && $index <= $lineMax) {
                if ($index === $line - 1) {
                    $preview[] = '<span style="background-color: #7fd189">' . rtrim($codeLine) . '</span>';
                } else {
                    $preview[] = rtrim($codeLine);
                }
            } elseif ($index > $lineMax) {
                break;
            }
        }

        return implode('<br />', $preview);
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
