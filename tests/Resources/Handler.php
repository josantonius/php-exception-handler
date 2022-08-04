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

use Throwable;

class Handler
{
    public function init(Throwable $exception): void
    {
        History::set(new self(), 'init', $exception);
    }
}
