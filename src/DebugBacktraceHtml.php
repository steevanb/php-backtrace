<?php

class DebugBacktraceHtml extends \DebugBacktrace
{
    /** @var ?string */
    protected static $callerRemoveFile = __FILE__;

    /**
     * @param int $offset
     * @param int|null $limit
     */
    public static function dump($offset = 0, $limit = null)
    {
        echo static::getDump(static::getBacktraces($offset + 1, $limit));
    }

    /**
     * @param int $offset
     * @param int|null $limit
     */
    public static function eDump($offset = 0, $limit = null)
    {
        static::dump($offset + 1, $limit);
        exit();
    }

    /**
     * @param array $backtraces
     * @return string
     */
    public static function getDump(array $backtraces)
    {
        $return = static::getStyles();
        $return .= static::getJavascript();

        $return .= '<div class="steevanb-backtrace-container">';
        $return .= static::getCallerDump();

        $return .= '
            <table class="table-backtrace">
                <tr>
                    <th>#</th>
                    <th>File#Line</th>
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

    /** @return string */
    protected static function getStyles()
    {
        return '
            <style type="text/css">
                .steevanb-backtrace-container {
                    padding: 3px;
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
                .steevanb-backtrace-caller {
                    padding: 3px;
                    background-color: #78a1c9;
                    color: white;
                    font-weight: bold;
                }
            </style>';
    }

    /** @return string */
    protected static function getJavascript()
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
        $filePath = null;
        $line = null;
        $previewId = $previewPrefix . '_' . $index;

        if ($backtrace['file'] !== null) {
            if (file_exists($backtrace['file'])) {
                $filePath = $backtrace['file'];
                $fileFound = true;

                if ($backtrace['line'] !== null) {
                    $line = $backtrace['line'];
                    $lineFound = true;
                } else {
                    $lineFound = false;
                }
            } elseif (substr($backtrace['file'], -16) === ' : eval()\'d code') {
                $fileAndLine = substr($backtrace['file'], 0, -16);
                $filePath = substr($fileAndLine, 0, strrpos($fileAndLine, '('));
                if (file_exists($filePath)) {
                    $line = substr($fileAndLine, strrpos($fileAndLine, '(') + 1, -1);
                    $fileFound = true;
                    $lineFound = true;
                } else {
                    $fileFound = false;
                    $lineFound = false;
                }
            } else {
                $fileFound = false;
                $lineFound = false;
            }
        } else {
            $fileFound = false;
            $lineFound = false;
        }

        if ($fileFound === false && $lineFound === false) {
            $fileLineHtml = '\Closure';
            $codePreview = null;
        } else {
            $codePreview = static::getCodePreview($filePath, $line);
            $fileLineHtml = '
                <a
                    title="' . static::getFilePath($filePath) . '"
                    onclick="steevanb_dev_showCodePreview(\'' . $previewId . '\')"
                >
                    ' . basename($filePath) . '#' . $line . '
                </a>
            ';
        }

        $html = '
            <tr' . ($index % 2 ? null : ' class="dark"') . '>
                <td>' . ($index + 1) . '</td>
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

    /** @return string */
    protected static function getCallerDump()
    {
        $caller = static::getCaller();

        $return = '<div class="steevanb-backtrace-caller">';
        $header = null;
        if (is_array($caller)) {
            $header .= isset($caller['file']) ? static::getFilePath($caller['file']) : '(Unknow file)';
            $header .= isset($caller['line']) ? '#' . $caller['line'] : '::(Unknow line)';
        } else {
            $header= 'Unkonw caller';
        }
        $return .= $header;
        $return .= '</div>';

        return $return;
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
}
