<?php

/*
 * This file is part of https://github.com/josantonius/php-exception-handler repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Josantonius\ExceptionHandler\Exceptions;

class WrongMethodNameException extends \Exception
{
    public function __construct()
    {
        parent::__construct('The method name must be of type string.');
    }
}
