<?php

/**
 * Messages router component
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter\Tests\stubs;

use Amp\Promise;
use Amp\Success;
use ServiceBus\Common\Context\ServiceBusContext;
use ServiceBus\Common\MessageExecutor\MessageExecutor;
use ServiceBus\Common\Messages\Message;

/**
 *
 */
final class DefaultMessageExecutor implements MessageExecutor
{
    /**
     * @inheritdoc
     */
    public function __invoke(Message $message, ServiceBusContext $context): Promise
    {
        return new Success();
    }
}
