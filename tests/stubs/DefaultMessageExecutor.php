<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter\Tests\stubs;

use Amp\Promise;
use Amp\Success;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Common\MessageExecutor\MessageExecutor;

/**
 *
 */
final class DefaultMessageExecutor implements MessageExecutor
{
    public function __invoke(object $message, ServiceBusContext $context): Promise
    {
        return new Success();
    }
}
