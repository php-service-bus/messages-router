<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\MessagesRouter\Exceptions;

/**
 *
 */
final class InvalidCommandClassSpecified extends \LogicException
{
    public static function wrongCommandClass(): self
    {
        return new self('The command class is not specified, or does not exist');
    }
}
