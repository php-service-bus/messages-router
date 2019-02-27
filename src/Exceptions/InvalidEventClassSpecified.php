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
final class InvalidEventClassSpecified extends \LogicException
{
    /**
     * @return self
     */
    public static function wrongEventClass(): self
    {
        return new self('The event class is not specified, or does not exist');
    }
}
