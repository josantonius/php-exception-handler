<?php

/*
* This file is part of https://github.com/josantonius/php-exception-handler repository.
*
* (c) Josantonius <hello@josantonius.dev>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Josantonius\ExceptionHandler\Tests\Resources\Exceptions;

use Josantonius\ExceptionHandler\Tests\Resources\History;

class TestException extends \Exception
{
    public function context(): void
    {
        History::set(new self(), 'context');
    }

    public function render(): void
    {
        History::set(new self(), 'render');
    }

    public function report(): void
    {
        History::set(new self(), 'report');
    }
}
