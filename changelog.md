### [2.1.0](../../compare/2.0.1...2.1.0) (2020-05-15)

- Set `DebugBacktraceConsole::dump()` and `DebugBacktraceConsole::eDump()` `$output` parameter facultative
- Add PHP 8 compatibility
- Add PHPUnit tests for PHP 5.4 to 8.0
- Move files from root to src (it's not a BC break because you must use this classes with autoload)

### [2.0.1](../../compare/2.0.0...2.0.1) (2017-03-30)

- Fix caller for \DebugBacktraceHtml and \DebugBacktraceConsole

### [2.0.0](../../compare/1.1.1...2.0.0) (2016-12-31)

- Reverse backtraces : first shown is first call (debug_backtrace() show last call in first)
- Split \DumpBacktrace in 3 classes : \DebugBacktrace, \DebugBacktraceHtml and \DebugBacktraceConsole
- Add dump for symfony/console applications

### [1.1.1](../../compare/1.1.0...1.1.1) (2016-10-12)

- Fix file not found errors
- Fix eval()'d code in file path
- Update backtrace.png

### [1.1.0](../../compare/1.0.1...1.1.0) (2016-10-10)

- Add eDump() and getDump()

### [1.0.1](../../compare/1.0.0...1.0.1) (2016-08-16)

- Fix (Unknow call) when function is called
- Add preview only when available
- Show \Closure in File::Line column, instead of (Unknow file)::(Unknow line)

### 1.0.0 (2016-08-02)

- Create \DumpBacktrace class
