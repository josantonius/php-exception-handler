# PHP ExceptionHandler library

[![Latest Stable Version](https://poser.pugx.org/josantonius/exception-handler/v/stable)](https://packagist.org/packages/josantonius/exception-handler)
[![License](https://poser.pugx.org/josantonius/exception-handler/license)](LICENSE)
[![Total Downloads](https://poser.pugx.org/josantonius/exception-handler/downloads)](https://packagist.org/packages/josantonius/exception-handler)
[![CI](https://github.com/josantonius/php-exception-handler/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/josantonius/php-exception-handler/actions/workflows/ci.yml)
[![CodeCov](https://codecov.io/gh/josantonius/php-exception-handler/branch/main/graph/badge.svg)](https://codecov.io/gh/josantonius/php-exception-handler)
[![PSR1](https://img.shields.io/badge/PSR-1-f57046.svg)](https://www.php-fig.org/psr/psr-1/)
[![PSR4](https://img.shields.io/badge/PSR-4-9b59b6.svg)](https://www.php-fig.org/psr/psr-4/)
[![PSR12](https://img.shields.io/badge/PSR-12-1abc9c.svg)](https://www.php-fig.org/psr/psr-12/)

**Translations**: [Español](.github/lang/es-ES/README.md)

PHP library for handling exceptions.

---

- [Requirements](#requirements)
- [Installation](#installation)
- [Available Classes](#available-classes)
  - [ExceptionHandler Class](#exceptionhandler-class)
- [Exceptions Used](#exceptions-used)
- [Usage](#usage)
- [Tests](#tests)
- [TODO](#todo)
- [Changelog](#changelog)
- [Contribution](#contribution)
- [Sponsor](#Sponsor)
- [License](#license)

---

## Requirements

This library is compatible with the PHP versions: 8.1.

## Installation

The preferred way to install this extension is through [Composer](http://getcomposer.org/download/).

To install **PHP ExceptionHandler library**, simply:

```console
composer require Josantonius/exception-handler
```

The previous command will only install the necessary files,
if you prefer to **download the entire source code** you can use:

```console
composer require josantonius/exception-handler --prefer-source
```

You can also **clone the complete repository** with Git:

```console
git clone https://github.com/josantonius/php-exception-handler.git
```

## Available Classes

### ExceptionHandler Class

```php
use Josantonius\ExceptionHandler\ExceptionHandler;
```

Sets a exception handler:

```php
/**
 * Sets a exception handler.
 *
 * @param callable $callback          Exception handler function.
 * @param array    $runBeforeCallback Method names to call in the exception before run callback.
 * @param array    $runAfterCallback  Method names to call in the exception after run callback.
 * 
 * @throws NotCallableException     if the callback is not callable.
 * @throws WrongMethodNameException if the method names are not string or are empty.
 * 
 * @see https://www.php.net/manual/en/functions.first_class_callable_syntax.php
 */
new ExceptionHandler(
    callable $callback,
    string[] $runBeforeCallback = [],
    string[] $runAfterCallback = []
);
```

## Exceptions Used

```php
use Josantonius\ExceptionHandler\Exceptions\NotCallableException;
```

```php
use Josantonius\ExceptionHandler\Exceptions\WrongMethodNameException;
```

## Usage

Examples of use for this library:

### Sets basic exception handler

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

function handler(\Throwable $exception) { /* do something */ }

new ExceptionHandler(
    callback: handler(...)
);

/**
 * If an exception is thrown, the following is executed:
 *
 * handler($exception)
 */
```

### Sets methods to execute before calling the callback

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function context(): void { /* do something */ }
}

class Handler {
    public function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* do something */
        }
    }
}

new ExceptionHandler(
    callback: (new Handler())->exceptions(...),
    runBeforeCallback: ['context']
);

/**
 * If FooException() is thrown, the following is executed:
 * 
 * FooException->context()
 * Handler->exceptions($exception)
 */
```

### Sets methods to execute after calling the callback

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function report(): void { /* do something */ }

    public function render(): void { /* do something */ }
}

class Handler {
    public static function exceptions(Throwable $exception): void
    {
        if ($exception instanceof FooException) {
            /* do something */
        }
    }
}

new ExceptionHandler(
    callback: Handler::exceptions(...),
    runAfterCallback: ['report', 'render']
);

/**
 * If FooException() is thrown, the following is executed:
 * 
 * Handler::exceptions($exception)
 * FooException->report()
 * FooException->render()
 */
```

### Sets methods to execute before and after calling the callback

```php
use Josantonius\ExceptionHandler\ExceptionHandler;

class FooException extends \Exception
{
    public function context(): void { /* do something */ }

    public function report(): void { /* do something */ }

    public function render(): void { /* do something */ }
}

function exceptionHandler(Throwable $exception) { /* do something */ }

new ExceptionHandler(
    callback: exceptionHandler(...),
    runBeforeCallback: ['context', 'logger'],
    runAfterCallback: ['report', 'render']
);

/**
 * If FooException() is thrown, the following is executed:
 * 
 * FooException->context()
 * exceptionHandler($exception)
 * FooException->report()
 * FooException->render()
 * 
 * FooException->logger() is ignored, does not exist in the exception.
 */
```

## Tests

To run [tests](tests) you just need [composer](http://getcomposer.org/download/) and to execute the following:

```console
git clone https://github.com/josantonius/php-exception-handler.git
```

```console
cd php-exception-handler
```

```console
composer install
```

Run unit tests with [PHPUnit](https://phpunit.de/):

```console
composer phpunit
```

Run code standard tests with [PHPCS](https://github.com/squizlabs/PHP_CodeSniffer):

```console
composer phpcs
```

Run [PHP Mess Detector](https://phpmd.org/) tests to detect inconsistencies in code style:

```console
composer phpmd
```

Run all previous tests:

```console
composer tests
```

## TODO

- [ ] Add new feature
- [ ] Improve tests
- [ ] Improve documentation
- [ ] Improve English translation in the README file
- [ ] Refactor code for disabled code style rules (see phpmd.xml and phpcs.xml)

## Changelog

Detailed changes for each release are documented in the
[release notes](https://github.com/josantonius/php-exception-handler/releases).

## Contribution

Please make sure to read the [Contributing Guide](.github/CONTRIBUTING.md), before making a pull
request, start a discussion or report a issue.

Thanks to all [contributors](https://github.com/josantonius/php-exception-handler/graphs/contributors)! :heart:

## Sponsor

If this project helps you to reduce your development time,
[you can sponsor me](https://github.com/josantonius#sponsor) to support my open source work :blush:

## License

This repository is licensed under the [MIT License](LICENSE).

Copyright © 2022-present, [Josantonius](https://github.com/josantonius#contact)
