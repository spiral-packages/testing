<?php

declare(strict_types=1);

namespace Spiral\Testing\Traits;

use Spiral\Boot\DispatcherInterface;
use Spiral\Core\Container;
use Spiral\Core\ContainerScope;
use Spiral\Core\Internal\Introspector;

trait TestableKernel
{
    /** @inheritDoc */
    public function getContainer(): Container
    {
        $scopedContainer = ContainerScope::getContainer();
        if (
            $scopedContainer instanceof Container &&
            Introspector::scopeName($scopedContainer) !== Container::DEFAULT_ROOT_SCOPE_NAME
        ) {
            return $scopedContainer;
        }

        return $this->container;
    }

    /** @return array<class-string<DispatcherInterface>> */
    public function getRegisteredDispatchers(): array
    {
        return \array_map(static fn (string|DispatcherInterface $dispatcher): string => \is_object($dispatcher)
            ? $dispatcher::class
            : $dispatcher,
            $this->dispatchers
        );
    }

    /** @return array<class-string> */
    public function getRegisteredBootloaders(): array
    {
        return $this->bootloader->getClasses();
    }
}
