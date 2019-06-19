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
    /**
     * @psalm-var \SplObjectStorage<\ServiceBus\MessagesRouter\RouterConfigurator, string>
     *
     * @var \SplObjectStorage
     */
    private $configurators;

    public function __construct()
    {
        /** @psalm-suppress MixedTypeCoercion */
        $this->configurators = new \SplObjectStorage();
    }

    /**
     * @param RouterConfigurator $configurator
     *
     * @return void
     */
    public function addConfigurator(RouterConfigurator $configurator): void
    {
        if (false === $this->configurators->contains($configurator))
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
