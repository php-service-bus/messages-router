<?php

/**
 * Messages router component
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
 * Messages router
 */
final class Router implements \Countable
{
    /**
     * Event listeners
     *
     * @psalm-var array<string, array<string|int, \ServiceBus\Common\MessageExecutor\MessageExecutor>>
     * @var array
     */
    private $listeners;

    /**
     * Command handlers
     *
     * @psalm-var array<string, \ServiceBus\Common\MessageExecutor\MessageExecutor>
     * @var array
     */
    private $handlers;

    /**
     * Event listeners count
     *
     * @var int
     */
    private $listenersCount = 0;

    /**
     * Command handlers count
     *
     * @var int
     */
    private $handlersCount = 0;

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->handlersCount + $this->listenersCount;
    }

    /**
     * Get registered listeners count
     *
     * @return int
     */
    public function listenersCount(): int
    {
        return $this->listenersCount;
    }

    /**
     * Get registered handlers count
     *
     * @return int
     */
    public function handlersCount(): int
    {
        return $this->handlersCount;
    }

    /**
     * @param object $message
     *
     * @psalm-return array<array-key, \ServiceBus\Common\MessageExecutor\MessageExecutor>
     * @return array
     */
    public function match(object $message): array
    {
        $messageClass = \get_class($message);

        if(true === isset($this->listeners[$messageClass]))
        {
            return $this->listeners[$messageClass];
        }

        if(true === isset($this->handlers[$messageClass]))
        {
            return [$this->handlers[$messageClass]];
        }

        return [];
    }

    /**
     * Add event listener
     * For each event there can be many listeners
     *
     * @param object|string   $event Event object or class
     * @param MessageExecutor $handler
     *
     * @return void
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified
     */
    public function registerListener($event, MessageExecutor $handler): void
    {

        $eventClass = true === \is_object($event) ? \get_class($event) : (string) $event;

        if('' !== $eventClass && true === \class_exists($eventClass))
        {
            $this->listeners[$eventClass][] = $handler;
            $this->listenersCount++;

            return;
        }

        throw InvalidEventClassSpecified::wrongEventClass();
    }

    /**
     * Register command handler
     * For 1 command there can be only 1 handler
     *
     * @param object|string   $command Command object or class
     * @param MessageExecutor $handler
     *
     * @return void
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified
     * @throws \ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed
     */
    public function registerHandler($command, MessageExecutor $handler): void
    {
        $commandClass = true === \is_object($command) ? \get_class($command) : (string) $command;

        if('' === $commandClass || false === \class_exists($commandClass))
        {
            throw InvalidCommandClassSpecified::wrongCommandClass();
        }

        if(true === isset($this->handlers[$commandClass]))
        {
            throw MultipleCommandHandlersNotAllowed::duplicate($commandClass);
        }

        $this->handlers[$commandClass] = $handler;
        $this->handlersCount++;
    }
}
