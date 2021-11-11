<?php

/**
 * Messages router component.
 *
 * @author  Maksim Masiukevich <contacts@desperado.dev>
 * @license MIT
 * @license https://opensource.org/licenses/MIT
 */

declare(strict_types=0);

namespace ServiceBus\MessagesRouter;

/**
 * Chaim routing configurator.
 */
final class ChainRouterConfigurator implements RouterConfigurator
{
    /**
     * @psalm-var \SplObjectStorage<RouterConfigurator, mixed>
     *
     * @var \SplObjectStorage
     */
    private $configurators;

    public function __construct()
    {
        /** @psalm-var \SplObjectStorage<RouterConfigurator, mixed> $configurators */
        $configurators = new \SplObjectStorage();

        $this->configurators = $configurators;
    }

    public function addConfigurator(RouterConfigurator $configurator): void
    {
        if ($this->configurators->contains($configurator) === false)
        {
            $this->configurators->attach($configurator);
        }
    }

    public function configure(Router $router): void
    {
        /** @var \ServiceBus\MessagesRouter\RouterConfigurator $configurator */
        foreach ($this->configurators as $configurator)
        {
            $configurator->configure($router);
        }
    }
}
