<?php

/*
* This file is part of https://github.com/josantonius/php-exception-handler repository.
*
* (c) Josantonius <hello@josantonius.dev>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Josantonius\ErrorHandler\Tests;

use ReflectionClass;
use PHPUnit\Framework\TestCase;
use Josantonius\ExceptionHandler\ExceptionHandler;
use Josantonius\ExceptionHandler\Tests\Resources\Handler;
use Josantonius\ExceptionHandler\Tests\Resources\History;
use Josantonius\ExceptionHandler\Exceptions\WrongMethodNameException;
use Josantonius\ExceptionHandler\Exceptions\NotCallableException;
use Josantonius\ExceptionHandler\Tests\Resources\Exceptions\TestException;

class ExceptionHandlerTest extends TestCase
{
    private Handler $handler;

    public function setUp(): void
    {
        parent::setUp();

        $this->handler = new Handler();
    }

    public function testShouldFailIfCallableCallbackIsNotPassed(): void
    {
        $this->expectException(NotCallableException::class);

        new ExceptionHandler(callback: 'foo');
    }

    public function testShouldFailIfTheMethodNamesNotContainValidDataType(): void
    {
        $this->expectException(WrongMethodNameException::class);

        new ExceptionHandler(
            callback: $this->handler->init(...),
            runBeforeCallback: [0, 8]
        );
    }

    public function testShouldSetTheHandlerOnlyWithTheCallback(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(callback: $this->handler->init(...))
        );
    }

    public function testShouldSetTheHandlerOnlyWithCallsToRunBefore(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(
                callback: $this->handler->init(...),
                runBeforeCallback: ['context']
            )
        );
    }

    public function testShouldSetTheHandlerOnlyWithCallsToAfter(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(
                callback: $this->handler->init(...),
                runAfterCallback: ['report', 'render']
            )
        );
    }

    public function testShouldCallTheCallbackWhenAnExceptionIsThrow(): void
    {
        $exceptionHandler = new ExceptionHandler(callback: $this->handler->init(...));

        History::clear();

        $this->simulateException($exceptionHandler);

        $this->assertCount(1, History::get());

        $this->assertInstanceOf(Handler::class, History::get(0));
        $this->assertInstanceOf(TestException::class, History::get(0)->exception);

        $this->assertEquals('init', History::get(0)->methodName);
    }

    public function testShouldCallTheCallbackBeforeRunMethodsWhenAnExceptionIsThrow(): void
    {
        $exceptionHandler = new ExceptionHandler(
            callback: $this->handler->init(...),
            runBeforeCallback: ['context', 'unknown'],
        );

        History::clear();

        $this->simulateException($exceptionHandler);

        $this->assertCount(2, History::get());

        $this->assertInstanceOf(TestException::class, History::get(0));
        $this->assertInstanceOf(Handler::class, History::get(1));

        $this->assertEquals('context', History::get(0)->methodName);
    }

    public function testShouldCallTheCallbackAfterRunMethodsWhenAnExceptionIsThrow(): void
    {
        $exceptionHandler = new ExceptionHandler(
            callback: $this->handler->init(...),
            runAfterCallback: ['report', 'render', 'unknown'],
        );

        History::clear();

        $this->simulateException($exceptionHandler);

        $this->assertCount(3, History::get());

        $this->assertInstanceOf(Handler::class, History::get(0));
        $this->assertInstanceOf(TestException::class, History::get(1));
        $this->assertInstanceOf(TestException::class, History::get(2));

        $this->assertEquals('report', History::get(1)->methodName);
        $this->assertEquals('render', History::get(2)->methodName);
    }

    public function testShouldCallTheCallbackAfterAndBeforeRunMethodsWhenAnExceptionIsThrow(): void
    {
        $exceptionHandler = new ExceptionHandler(
            callback: $this->handler->init(...),
            runBeforeCallback: ['context', 'unknown'],
            runAfterCallback: ['report', 'render', 'unknown'],
        );

        History::clear();

        $this->simulateException($exceptionHandler);

        $this->assertCount(4, History::get());

        $this->assertInstanceOf(TestException::class, History::get(0));
        $this->assertInstanceOf(Handler::class, History::get(1));
        $this->assertInstanceOf(TestException::class, History::get(2));
        $this->assertInstanceOf(TestException::class, History::get(3));

        $this->assertEquals('context', History::get(0)->methodName);
        $this->assertEquals('report', History::get(2)->methodName);
        $this->assertEquals('render', History::get(3)->methodName);
    }

    private function simulateException(ExceptionHandler $object): void
    {
        $reflection = new ReflectionClass($object);
        $reflection = $reflection->getMethod('handler');
        $reflection->setAccessible(true);
        $reflection->invoke($object, new TestException());
    }
}
