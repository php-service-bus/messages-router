<?php /** @noinspection PhpUnhandledExceptionInspection */

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter\Tests;

use PHPUnit\Framework\TestCase;
use ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified;
use ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified;
use ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed;
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
     * @test
     */
    public function emptyEventClass(): void
    {
        $this->expectException(InvalidEventClassSpecified::class);
        $this->expectExceptionMessage('The event class is not specified, or does not exist');

        (new Router())->registerListener('', new  DefaultMessageExecutor());
    }

    /**
     * @test
     */
    public function unExistsEventClass(): void
    {
        $this->expectException(InvalidEventClassSpecified::class);
        $this->expectExceptionMessage('The event class is not specified, or does not exist');

        (new Router())->registerListener('SomeEventClass', new DefaultMessageExecutor());
    }

    /**
     * @test
     */
    public function emptyCommandClass(): void
    {
        $this->expectException(InvalidCommandClassSpecified::class);
        $this->expectExceptionMessage('The command class is not specified, or does not exist');

        (new Router())->registerHandler('', new DefaultMessageExecutor());
    }

    /**
     * @test
     */
    public function unExistsCommandClass(): void
    {
        $this->expectException(InvalidCommandClassSpecified::class);
        $this->expectExceptionMessage('The command class is not specified, or does not exist');

        (new Router())->registerHandler('SomeCommandClass', new DefaultMessageExecutor());
    }

    /**
     * @test
     */
    public function duplicateCommand(): void
    {
        $this->expectException(MultipleCommandHandlersNotAllowed::class);
        $this->expectExceptionMessage(\sprintf(
            'A handler has already been registered for the "%s" command',
            TestCommand::class
        ));

        $handler = new DefaultMessageExecutor();

        $router = new Router();

        $router->registerHandler(TestCommand::class, $handler);
        $router->registerHandler(TestCommand::class, $handler);
    }

    /**
     * @test
     */
    public function successRegister(): void
    {
        $handler = new DefaultMessageExecutor();

        $router = new Router();

        self::assertCount(0, $router->match(new TestCommand()));
        self::assertCount(0, $router->match(new SecondTestCommand()));

        $router->registerHandler(TestCommand::class, $handler);

        self::assertCount(1, $router);
        self::assertSame(0, $router->listenersCount());
        self::assertSame(1, $router->handlersCount());

        $router->registerListener(TestEvent::class, $handler);
        $router->registerListener(TestEvent::class, $handler);
        $router->registerListener(SecondTestEvent::class, $handler);

        self::assertSame(3, $router->listenersCount());
        self::assertSame(1, $router->handlersCount());
        self::assertCount(4, $router);

        self::assertCount(1, $router->match(new TestCommand()));
        self::assertCount(2, $router->match(new TestEvent()));
    }
}
