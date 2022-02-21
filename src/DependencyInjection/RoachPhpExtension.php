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

namespace Nelexa\RoachPhpBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

/**
 * @internal
 */
final class RoachPhpExtension extends Extension
{
    public const ALIAS = 'roach_php';

    public const TAGS = [
        \RoachPHP\Spider\SpiderInterface::class => 'roach_php.spider',
        \RoachPHP\Support\ConfigurableInterface::class => 'roach_php.configurable',
        \RoachPHP\Extensions\ExtensionInterface::class => 'roach_php.extension',
    ];

    public const CLEAR_EVENT_SUBSCRIBER_TAGS = [
        self::TAGS[\RoachPHP\Extensions\ExtensionInterface::class],
    ];

    public function getAlias(): string
    {
        return self::ALIAS;
    }

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');

        $this->autoconfigureTags($container);
    }

    private function autoconfigureTags(ContainerBuilder $container): void
    {
        foreach (self::TAGS as $className => $tag) {
            $container->registerForAutoconfiguration($className)->addTag($tag);
        }
    }
}
