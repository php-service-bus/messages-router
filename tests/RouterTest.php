<?php

/**
 * Messages router component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter\Tests;

use PHPUnit\Framework\TestCase;
use ServiceBus\MessagesRouter\Router;
use ServiceBus\MessagesRouter\Tests\stubs\DefaultMessageExecutor;
use ServiceBus\MessagesRouter\Tests\stubs\SecondTestCommand;
use ServiceBus\MessagesRouter\Tests\stubs\SecondTestEvent;
use ServiceBus\MessagesRouter\Tests\stubs\TestCommand;
use ServiceBus\MessagesRouter\Tests\stubs\TestEvent;

/**
 *
 */
final class RouterTest extends TestCase
{
    /**
     * @inheritdoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        Router::instance()->resetInstance();
    }


    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified
     * @expectedExceptionMessage The event class is not specified, or does not exist
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function emptyEventClass(): void
    {
        Router::instance()->registerListener('', new  DefaultMessageExecutor());
    }

    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified
     * @expectedExceptionMessage The event class is not specified, or does not exist
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function unExistsEventClass(): void
    {
        Router::instance()->registerListener('SomeEventClass', new DefaultMessageExecutor());
    }

    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified
     * @expectedExceptionMessage The command class is not specified, or does not exist
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function emptyCommandClass(): void
    {
        Router::instance()->registerHandler('', new DefaultMessageExecutor());
    }

    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified
     * @expectedExceptionMessage The command class is not specified, or does not exist
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function unExistsCommandClass(): void
    {
        Router::instance()->registerHandler('SomeCommandClass', new DefaultMessageExecutor());
    }

    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed
     * @expectedExceptionMessage A handler has already been registered for the
     *                           "ServiceBus\Tests\Stubs\Messages\FirstEmptyCommand" command
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function duplicateCommand(): void
    {
        $handler = new DefaultMessageExecutor();

        Router::instance()->registerHandler(TestCommand::class, $handler);
        Router::instance()->registerHandler(TestCommand::class, $handler);
    }

    /**
     * @test
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function successRegister(): void
    {
        $handler = new DefaultMessageExecutor();

        $router = Router::instance();

        static::assertCount(0, Router::instance()->match(new TestCommand));
        static::assertCount(0, $router->match(new SecondTestCommand()));

        $router->registerHandler(TestCommand::class, $handler);

        static::assertCount(1, $router);
        static::assertSame(0, $router->listenersCount());
        static::assertSame(1, $router->handlersCount());

        $router->registerListener(TestEvent::class, $handler);
        $router->registerListener(TestEvent::class, $handler);
        $router->registerListener(SecondTestEvent::class, $handler);

        static::assertSame(3, $router->listenersCount());
        static::assertSame(1, $router->handlersCount());
        static::assertCount(4, $router);

        static::assertCount(1, $router->match(new TestCommand));
        static::assertCount(2, $router->match(new TestEvent));
    }

    /**
     * @test
     * @expectedException \ServiceBus\MessagesRouter\Exceptions\CantAddExecutorToClosedRouter
     *
     * @return void
     *
     * @throws \Throwable
     */
    public function addToClosed(): void
    {
        Router::instance()->close();
        Router::instance()->registerListener(new TestEvent(), new DefaultMessageExecutor());
    }
}
