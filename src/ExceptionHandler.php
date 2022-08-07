<?php

declare(strict_types=1);

/*
* This file is part of https://github.com/josantonius/php-exception-handler repository.
*
* (c) Josantonius <hello@josantonius.dev>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Josantonius\ExceptionHandler;

use Throwable;
use Josantonius\ExceptionHandler\Exceptions\NotCallableException;
use Josantonius\ExceptionHandler\Exceptions\WrongMethodNameException;

/**
 * Handling exceptions.
 */
class ExceptionHandler
{
    /**
     * Sets a exception handler.
     *
     * @param callable $callback          Exception handler function.
     * @param string[] $runBeforeCallback Method names to call in the exception before run callback.
     * @param string[] $runAfterCallback  Method names to call in the exception after run callback.
     *
     * @throws NotCallableException     if the callback is not callable.
     * @throws WrongMethodNameException if the method names are not strings.
     */
    public function __construct(
        private $callback,
        private array $runBeforeCallback = [],
        private array $runAfterCallback = []
    ) {
        if (!is_callable($callback)) {
            throw new NotCallableException();
        }

        $methodNames = array_merge($runBeforeCallback, $runAfterCallback);

        if (array_filter($methodNames, fn ($method) => !is_string($method))) {
            throw new WrongMethodNameException();
        }

        set_exception_handler(fn (Throwable $exception) => $this->handler($exception));
    }

    /**
     * Handle exception.
     */
    private function handler(Throwable $exception): void
    {
        foreach ($this->runBeforeCallback as $method) {
            if (method_exists($exception, $method)) {
                $exception->$method();
            }
        }

        ($this->callback)($exception);

        foreach ($this->runAfterCallback as $method) {
            if (method_exists($exception, $method)) {
                $exception->$method();
            }
        }
    }
}
