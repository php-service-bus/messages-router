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
 * Chaim routing configurator.
 */
final class ChainRouterConfigurator implements RouterConfigurator
{
    private \SplObjectStorage $configurators;

    public function __construct()
    {
        $this->configurators = new \SplObjectStorage();
    }

    public function addConfigurator(RouterConfigurator $configurator): void
    {
        if ($this->configurators->contains($configurator) === false)
        {
            $this->configurators->attach($configurator);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configure(Router $router): void
    {
        /** @var \ServiceBus\MessagesRouter\RouterConfigurator $configurator */
        foreach ($this->configurators as $configurator)
        {
            $configurator->configure($router);
        }
    }
}
