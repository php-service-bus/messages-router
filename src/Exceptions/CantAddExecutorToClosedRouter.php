<?php

/**
 * Messages router component
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
final class CantAddExecutorToClosedRouter extends \LogicException
{
    public function __construct()
    {
        parent::__construct('Unable to add handler: router configuration completed');
    }
}
