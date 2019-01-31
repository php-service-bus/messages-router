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
use ServiceBus\Common\Messages\Command;
use ServiceBus\Common\Messages\Event;
use ServiceBus\Common\Messages\Message;
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
     * @var array<string, array<string|int, \ServiceBus\Common\MessageExecutor\MessageExecutor>>
     */
    private $listeners;

    /**
     * Command handlers
     *
     * @var array<string, \ServiceBus\Common\MessageExecutor\MessageExecutor>
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
     * @param Message $message
     *
     * @return array<array-key, \ServiceBus\Common\MessageExecutor\MessageExecutor>
     */
    public function match(Message $message): array
    {
        $messageClass = \get_class($message);

        if($message instanceof Event)
        {
            return $this->listeners[$messageClass] ?? [];
        }

        return true === isset($this->handlers[$messageClass])
            ? [$this->handlers[$messageClass]]
            : [];
    }

    /**
     * Add event listener
     * For each event there can be many listeners
     *
     * @param Event|string    $event Event object or class
     * @param MessageExecutor $handler
     *
     * @return void
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidEventClassSpecified
     */
    public function registerListener($event, MessageExecutor $handler): void
    {

        $eventClass = $event instanceof Event
            ? \get_class($event)
            : (string) $event;

        if('' !== $eventClass && true === \class_exists($eventClass))
        {
            $this->listeners[$eventClass][] = $handler;
            $this->listenersCount++;

            return;
        }

        throw new InvalidEventClassSpecified('The event class is not specified, or does not exist');
    }

    /**
     * Register command handler
     * For 1 command there can be only 1 handler
     *
     * @param Command|string  $command Command object or class
     * @param MessageExecutor $handler
     *
     * @return void
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\InvalidCommandClassSpecified
     * @throws \ServiceBus\MessagesRouter\Exceptions\MultipleCommandHandlersNotAllowed
     */
    public function registerHandler($command, MessageExecutor $handler): void
    {
        $commandClass = $command instanceof Command ? \get_class($command) : (string) $command;

        if('' === $commandClass || false === \class_exists($commandClass))
        {
            throw new InvalidCommandClassSpecified('The command class is not specified, or does not exist');
        }

        if(true === isset($this->handlers[$commandClass]))
        {
            throw new MultipleCommandHandlersNotAllowed(
                \sprintf('A handler has already been registered for the "%s" command', $commandClass)
            );
        }

        $this->handlers[$commandClass] = $handler;
        $this->handlersCount++;
    }
}
