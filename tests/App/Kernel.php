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

namespace Nelexa\RoachPhpBundle\Tests\App;

use RoachPHP\Testing\FakeLogger;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MakerBundle\MakerBundle(),
            new \Nelexa\RoachPhpBundle\RoachPhpBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $parameters = $container->parameters();

        $parameters->set('container.autowiring.strict_mode', false);
        $parameters->set('locale', 'en');
        $parameters->set('kernel.secret', '$eCrEt');
        $parameters->set('router.request_context.host', 'example.org');
        $parameters->set('router.request_context.scheme', 'https');
        $parameters->set('router.request_context.base_url', '/');
        $parameters->set('asset.request_context.base_path', '%router.request_context.base_url%');
        $parameters->set('asset.request_context.secure', true);

        $container->extension('framework', [
            'secret' => '%kernel.secret%',
            'test' => $_SERVER['APP_ENV'] === 'test',
        ]);

        $container
            ->services()
            ->defaults()
            ->autowire(true)
            ->autoconfigure(true)
            ->public()
            ->load('Nelexa\RoachPhpBundle\Tests\App\\', __DIR__ . '/*')
            ->exclude(__DIR__ . '/{DependencyInjection,Entity,Tests,Kernel.php}')
        ;

        $container->services()->set(FakeLogger::class);
        $container->services()->alias('roach_php.logger', FakeLogger::class);
    }
}
