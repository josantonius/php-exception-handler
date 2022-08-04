<?php

/*
* This file is part of https://github.com/josantonius/php-exception-handler repository.
*
* (c) Josantonius <hello@josantonius.dev>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Josantonius\ExceptionHandler\Tests\Resources;

use Exception;

class History
{
    private static array $history = [];

    public static function set(object $object, string $methodName, ?Exception $exception = null)
    {
        $object->exception  = $exception;
        $object->methodName = $methodName;

        self::$history[] = $object;
    }

    public static function get(?int $key = null): mixed
    {
        return $key !== null ? (self::$history[$key] ?? null) : self::$history;
    }

    public static function clear(): void
    {
        self::$history = [];
    }
}
