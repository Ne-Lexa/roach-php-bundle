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

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_locator;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->public()
        ->autoconfigure(false)
        ->autowire(false)
    ;

    // Scheduling
    $services
        ->set('roach_php.request_scheduler', \RoachPHP\Scheduling\ArrayRequestScheduler::class)
        ->args([service(\RoachPHP\Scheduling\Timing\ClockInterface::class)])
    ;
    $services->alias(
        \RoachPHP\Scheduling\RequestSchedulerInterface::class,
        'roach_php.request_scheduler'
    );
    $services->set('roach_php.clock', \RoachPHP\Scheduling\Timing\SystemClock::class);
    $services->alias(
        \RoachPHP\Scheduling\Timing\ClockInterface::class,
        'roach_php.clock'
    );

    // Http
    $services->set('roach_php.client', \RoachPHP\Http\Client::class);
    $services->alias(\RoachPHP\Http\ClientInterface::class, 'roach_php.client');

    // ItemPipeline
    $services
        ->set('roach_php.item_pipeline', \RoachPHP\ItemPipeline\ItemPipeline::class)
        ->args([
            service('event_dispatcher'),
        ])
    ;
    $services->alias(\RoachPHP\ItemPipeline\ItemPipelineInterface::class, 'roach_php.item_pipeline');

    // Spider
    $services
        ->set(\RoachPHP\Spider\Processor::class)
        ->args([
            service('event_dispatcher'),
        ])
    ;
    $services->set(\RoachPHP\Spider\Middleware\MaximumCrawlDepthMiddleware::class);

    $services
        ->set('roach_php.logger.stream_handler', \Monolog\Handler\StreamHandler::class)
        ->args(['php://stdout'])
    ;
    $services
        ->set('roach_php.logger', \Monolog\Logger::class)
        ->args([
            'roach',
            [
                service('roach_php.logger.stream_handler'),
            ],
        ])
    ;

    $services
        ->set(\RoachPHP\Core\Engine::class)
        ->args([
            service(\RoachPHP\Scheduling\RequestSchedulerInterface::class),
            service(\RoachPHP\Downloader\Downloader::class),
            service(\RoachPHP\ItemPipeline\ItemPipelineInterface::class),
            service(\RoachPHP\Spider\Processor::class),
            service('event_dispatcher'),
        ])
    ;

    // Downloader and downloader middlewares
    $services
        ->set(\RoachPHP\Downloader\Downloader::class)
        ->args([
            service(\RoachPHP\Http\ClientInterface::class),
            service('event_dispatcher'),
        ])
    ;
    $services->set(\RoachPHP\Downloader\Middleware\CookieMiddleware::class);
    $services
        ->set(\RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware::class)
        ->args([
            service('roach_php.logger'),
        ])
    ;
    $services->set(\RoachPHP\Downloader\Middleware\RobotsTxtMiddleware::class);
    $services->set(\RoachPHP\Downloader\Middleware\UserAgentMiddleware::class);

    // Extensions
    $services
        ->set(\RoachPHP\Extensions\LoggerExtension::class)
        ->args([
            service('roach_php.logger'),
        ])
    ;
    $services->set(\RoachPHP\Extensions\MaxRequestExtension::class);
    $services
        ->set(\RoachPHP\Extensions\StatsCollectorExtension::class)
        ->args([
            service('roach_php.logger'),
            service('roach_php.clock'),
        ])
    ;

    // commands
    $services->set(\RoachPHP\Shell\Repl::class)->tag('console.command');
    $services
        ->set(\Nelexa\RoachPhpBundle\Command\RunSpiderCommand::class)
        ->args([
            tagged_locator('roach_php.spider'),
        ])
        ->tag('console.command')
    ;

    // maker
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\MakeSpider::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\MakeExtension::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\MakeItemProcessor::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\Downloader\MakeRequestMiddleware::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\Downloader\MakeResponseMiddleware::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\Spider\MakeRequestMiddleware::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\Spider\MakeResponseMiddleware::class)
        ->tag('maker.command')
    ;
    $services
        ->set(\Nelexa\RoachPhpBundle\Maker\Spider\MakeItemMiddleware::class)
        ->tag('maker.command')
    ;
};
