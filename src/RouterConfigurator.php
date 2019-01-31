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

/**
 *
 */
interface RouterConfigurator
{
    /**
     * Configure message routes
     *
     * @param Router $router
     *
     * @return void
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\MessageRouterConfigurationFailed
     */
    public function configure(Router $router): void;
}
