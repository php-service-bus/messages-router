<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter\Exceptions;

/**
 *
 */
final class MultipleCommandHandlersNotAllowed extends \LogicException
{
    public static function duplicate(string $commandClass): self
    {
        return new self(\sprintf('A handler has already been registered for the "%s" command', $commandClass));
    }
}
