<?php

/*
 * This file is part of https://github.com/josantonius/php-exception-handler repository.
 *
 * (c) Josantonius <hello@josantonius.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
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

    public function test_should_fail_if_callable_callback_is_not_passed(): void
    {
        $this->expectException(NotCallableException::class);

        new ExceptionHandler(callback: 'foo');
    }

    public function test_should_fail_if_the_method_names_not_contain_valid_data_type(): void
    {
        $this->expectException(WrongMethodNameException::class);

        new ExceptionHandler(
            callback: $this->handler->init(...),
            runBeforeCallback: [0, 8]
        );
    }

    public function test_should_set_the_handler_only_with_the_callback(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(callback: $this->handler->init(...))
        );
    }

    public function test_should_set_the_handler_only_with_calls_to_run_before(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(
                callback: $this->handler->init(...),
                runBeforeCallback: ['context']
            )
        );
    }

    public function test_should_set_the_handler_only_with_calls_to_after(): void
    {
        $this->assertInstanceOf(
            ExceptionHandler::class,
            new ExceptionHandler(
                callback: $this->handler->init(...),
                runAfterCallback: ['report', 'render']
            )
        );
    }

    public function test_should_call_the_callback_when_an_exception_is_throw(): void
    {
        $exceptionHandler = new ExceptionHandler(callback: $this->handler->init(...));

        History::clear();

        $this->simulateException($exceptionHandler);

        $this->assertCount(1, History::get());

        $this->assertInstanceOf(Handler::class, History::get(0));
        $this->assertInstanceOf(TestException::class, History::get(0)->exception);

        $this->assertEquals('init', History::get(0)->methodName);
    }

    public function test_should_call_the_callback_before_run_methods_when_exception_is_throw(): void
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

    public function test_should_call_the_callback_after_run_methods_when_exception_is_throw(): void
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

    public function test_should_call_callback_and_all_methods_when_an_exception_is_throw(): void
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
