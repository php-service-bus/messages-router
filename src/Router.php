<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter;

use ServiceBus\Common\MessageExecutor\MessageExecutor;
use ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified;
use ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified;
use ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed;

/**
 * Messages router.
 */
final class Router implements \Countable
{
    /**
     * Event listeners.
     *
     * @psalm-var array<string, array<string|int, \ServiceBus\Common\MessageExecutor\MessageExecutor>>
     *
     * @var MessageExecutor[][]
     */
    private $listeners = [];

    /**
     * Command handlers.
     *
     * @psalm-var array<string, \ServiceBus\Common\MessageExecutor\MessageExecutor>
     *
     * @var MessageExecutor[]
     */
    private $handlers = [];

    /**
     * Event listeners count.
     *
     * @var int
     */
    private $listenersCount = 0;

    /**
     * Command handlers count.
     *
     * @var int
     */
    private $handlersCount = 0;

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return $this->handlersCount + $this->listenersCount;
    }

    /**
     * Get registered listeners count.
     */
    public function listenersCount(): int
    {
        return $this->listenersCount;
    }

    /**
     * Get registered handlers count.
     */
    public function handlersCount(): int
    {
        return $this->handlersCount;
    }

    /**
     * @param object $message
     *
     * @psalm-return array<array-key, \ServiceBus\Common\MessageExecutor\MessageExecutor>
     */
    public function match(object $message): array
    {
        $messageClass = \get_class($message);

        if (isset($this->listeners[$messageClass]) === true)
        {
            return $this->listeners[$messageClass];
        }

        if (isset($this->handlers[$messageClass]) === true)
        {
            return [$this->handlers[$messageClass]];
        }

        return [];
    }

    /**
     * Add event listener
     * For each event there can be many listeners.
     *
     * @param object|string $event Event object or class
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified
     */
    public function registerListener($event, MessageExecutor $handler): void
    {
        $eventClass = \is_object($event) === true ? \get_class($event) : (string) $event;

        if ($eventClass !== '' && \class_exists($eventClass) === true)
        {
            $this->listeners[$eventClass][] = $handler;
            $this->listenersCount++;

            return;
        }

        throw InvalidEventClassSpecified::wrongEventClass();
    }

    /**
     * Register command handler
     * For 1 command there can be only 1 handler.
     *
     * @param object|string $command Command object or class
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified
     * @throws \ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed
     */
    public function registerHandler($command, MessageExecutor $handler): void
    {
        $commandClass = \is_object($command) === true ? \get_class($command) : (string) $command;

        if ($commandClass === '' || \class_exists($commandClass) === false)
        {
            throw InvalidCommandClassSpecified::wrongCommandClass();
        }

        if (isset($this->handlers[$commandClass]) === true)
        {
            throw MultipleCommandHandlersNotAllowed::duplicate($commandClass);
        }

        $this->handlers[$commandClass] = $handler;
        $this->handlersCount++;
    }
}
