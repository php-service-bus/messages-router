<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\MessagesRouter\Exceptions;

final class InvalidEventClassSpecified extends \LogicException
{
    public static function wrongEventClass(): self
    {
        return new self('The event class is not specified, or does not exist');
    }
}
