2.0.0 (2016-12-31)
------------------

- Reverse backtraces : first shown is first call (debug_backtrace() show last call in first)
- Split \DumpBacktrace in 3 classes : \DebugBacktrace, \DebugBacktraceHtml and \DebugBacktraceConsole
- Add dump for symfony/console applications

1.1.1 (2016-10-12)
------------------

- Fix file not found errors
- Fix eval()'d code in file path
- Update backtrace.png

1.1.0 (2016-10-10)
------------------

- Add eDump() and getDump()

1.0.1 (2016-08-16)
------------------

- Fix (Unknow call) when function is called
- Add preview only when available
- Show \Closure in File::Line column, instead of (Unknow file)::(Uknow line)

1.0.0 (2016-08-02)
------------------

- Create \DumpBacktrace class
