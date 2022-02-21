<?php

declare(strict_types=1);

/*
 * Copyright (c) 2022 Ne-Lexa <alexey@nelexa.ru>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/Ne-Lexa/roach-php-bundle
 */

namespace Nelexa\RoachPhpBundle\DependencyInjection\Compiler;

use Nelexa\RoachPhpBundle\DependencyInjection\RoachPhpExtension;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class RoachCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $tags = array_values(RoachPhpExtension::TAGS);

        foreach ($this->iterateTags($container, $tags) as $id) {
            $container
                ->getDefinition($id)
                ->setPublic(true)
                ->setAutowired(true)
                ->setAutoconfigured(true)
            ;
        }

        foreach ($this->iterateTags($container, RoachPhpExtension::CLEAR_EVENT_SUBSCRIBER_TAGS) as $id) {
            $container->getDefinition($id)->clearTag('kernel.event_subscriber');
        }
    }

    /**
     * @param array<string> $serviceTags
     *
     * @return iterable<string>
     */
    private function iterateTags(ContainerBuilder $container, array $serviceTags): iterable
    {
        foreach ($serviceTags as $tag) {
            foreach (array_keys($container->findTaggedServiceIds($tag)) as $id) {
                yield $id;
            }
        }
    }
}
