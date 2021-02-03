<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types = 0);

namespace ServiceBus\MessagesRouter;

/**
 * Routing Configurator.
 */
interface RouterConfigurator
{
    /**
     * Configure message routes.
     *
     * @throws \ServiceBus\MessagesRouter\Exceptions\MessageRouterConfigurationFailed
     */
    public function configure(Router $router): void;
}
