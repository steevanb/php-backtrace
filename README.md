[![version](https://img.shields.io/badge/version-1.0.1-green.svg)](https://github.com/steevanb/php-backtrace/tree/1.0.1)
[![php](https://img.shields.io/badge/php->=5.4-blue.svg)](http://php.net)
![Lines](https://img.shields.io/badge/code lines-380-green.svg)
![Total Downloads](https://poser.pugx.org/steevanb/php-backtrace/downloads)
[![SensionLabsInsight](https://img.shields.io/badge/SensionLabsInsight-platinum-brightgreen.svg)](https://insight.sensiolabs.com/projects/2b6dd6a0-ef48-4b5b-ba13-e825e0841be3/analyses/4)
[![Scrutinizer](https://scrutinizer-ci.com/g/steevanb/php-backtrace/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/steevanb/php-backtrace/)

php-backtrace
-------------

Show nice equivalent to debug_backtrace(), with caller, code preview etc.

Installation
------------

```bash
composer require-dev steevanb/php-backtrace ^1.0
```

Configuration
-------------

```php
// configure how file paths will be shown
// true : remove path prefix, based on DumpBacktrace.php path (assume it is in vendor/ dir)
// false : do not remove anything in file paths
// string : remove this prefix
\DumpBacktrace::setRemovePathPrefix($remove);
```

Usage
-----

```php
\DumpBacktrace::dump($offset = 0, $limit = null);
```
![PHP Backtrace](backtrace.png)

```php
\DumpBacktrace::getBacktraces($offset = 0, $limit = null);
```
