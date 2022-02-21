<?php

namespace Nelexa\RoachPhpBundle\DependencyInjection\Compiler;

use RoachPHP\Roach;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final class RoachCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        // does the trick and inject the container even if its a static method
//        $container
//            ->getDefinition(Roach::class)
//            ->addMethodCall('useContainer', [new Reference('service_container')])
//            ->setPublic(true);

        foreach ($container->findTaggedServiceIds('roach_php.spider') as $id => $tags) {
            $container->getDefinition($id)->setPublic(true)->setAutowired(true);
        }

        foreach ($container->findTaggedServiceIds('roach_php.item_processor') as $id => $tags) {
            $container->getDefinition($id)->setPublic(true)->setAutowired(true);
        }

        foreach ($container->findTaggedServiceIds('roach_php.extension') as $id => $tags) {
            $container->getDefinition($id)->setPublic(true)->setAutowired(true);
        }

        foreach ($container->findTaggedServiceIds('roach_php.request_middleware') as $id => $tags) {
            $container->getDefinition($id)->setPublic(true)->setAutowired(true);
        }
    }
}
