<?php

namespace Nelexa\RoachPhpBundle;

use Nelexa\RoachPhpBundle\DependencyInjection\RoachPhpExtension;
use RoachPHP\Roach;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RoachPhpBundle extends Bundle
{
    public function boot(): void
    {
        parent::boot();
        Roach::useContainer($this->container);
    }

    public function getContainerExtension(): ?ExtensionInterface
    {
        if ($this->extension === null) {
            $this->extension = new RoachPhpExtension();
        }

        return $this->extension;
    }

//    public function build(ContainerBuilder $container): void
//    {
//        $container->addCompilerPass(new RoachCompilerPass());
//    }
}
