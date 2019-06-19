<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <dev@async-php.com>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 1);

namespace ServiceBus\MessagesRouter;

/**
 * Routing Configurator.
 */
interface RouterConfigurator
{
    /**
     * Configure message routes.
     *
     * @param Router $router
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\MessageRouterConfigurationFailed
     *
     * @return void
     */
    public function configure(Router $router): void;
}
