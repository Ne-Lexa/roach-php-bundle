<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
        ->public()
        ->autoconfigure(false)
        ->autowire(true);

    // Scheduling
    $services
        ->set('roach_php.request_scheduler', \RoachPHP\Scheduling\ArrayRequestScheduler::class)
        ->args([service(\RoachPHP\Scheduling\Timing\ClockInterface::class)]);
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
            service('event_dispatcher')
        ]);
    $services->alias(\RoachPHP\ItemPipeline\ItemPipelineInterface::class, 'roach_php.item_pipeline');
    $services->instanceof(\RoachPHP\ItemPipeline\Processors\ItemProcessorInterface::class)->autowire(true)->public();

    // Spider
    $services
        ->instanceof(\RoachPHP\Spider\SpiderInterface::class)
        ->public()
        ->autowire(true)
        ->tag('roach_php.spider')
    ;
    $services
        ->set(\RoachPHP\Spider\Processor::class)
        ->args([
            service('event_dispatcher')
        ])
    ;
    $services->set(\RoachPHP\Spider\Middleware\MaximumCrawlDepthMiddleware::class)->public();
    $services->instanceof(\RoachPHP\Spider\SpiderMiddlewareInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Spider\Middleware\ItemMiddlewareInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Spider\Middleware\RequestMiddlewareInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Spider\Middleware\ResponseMiddlewareInterface::class)->autowire(true)->public();

    $services
        ->instanceof(\RoachPHP\Support\ConfigurableInterface::class)
        ->public()
        ->autowire(true)
    ;

    $services->alias('roach_php.logger', 'logger');

    $services
        ->set(\RoachPHP\Core\Engine::class)
        ->args([
            service(\RoachPHP\Scheduling\RequestSchedulerInterface::class),
            service(\RoachPHP\Downloader\Downloader::class),
            service(\RoachPHP\ItemPipeline\ItemPipelineInterface::class),
            service(\RoachPHP\Spider\Processor::class),
            service('event_dispatcher')
        ])
    ;

    // Downloader and downloader middlewares
    $services
        ->set(\RoachPHP\Downloader\Downloader::class)
        ->args([
            service(\RoachPHP\Http\ClientInterface::class),
            service('event_dispatcher')
        ])
    ;
    $services->set(\RoachPHP\Downloader\Middleware\CookieMiddleware::class)->public();
    $services
        ->set(\RoachPHP\Downloader\Middleware\RequestDeduplicationMiddleware::class)
        ->args([
            service('roach_php.logger')
        ])
        ->public();
    $services->set(\RoachPHP\Downloader\Middleware\RobotsTxtMiddleware::class)->public();
    $services->set(\RoachPHP\Downloader\Middleware\UserAgentMiddleware::class)->public();
    $services->instanceof(\RoachPHP\Downloader\DownloaderMiddlewareInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Downloader\Middleware\RequestMiddlewareInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Downloader\Middleware\ResponseMiddlewareInterface::class)->autowire(true)->public();

    // Extensions
    $services
        ->set(\RoachPHP\Extensions\LoggerExtension::class)
        ->args([
            service('roach_php.logger')
        ])
        ->public();
    $services->set(\RoachPHP\Extensions\MaxRequestExtension::class)->public();
    $services
        ->set(\RoachPHP\Extensions\StatsCollectorExtension::class)
        ->args([
            service('roach_php.logger'),
            service('roach_php.clock')
        ])
        ->public();


    $services->instanceof(\RoachPHP\Extensions\ExtensionInterface::class)->autowire(true)->public();
    $services->instanceof(\RoachPHP\Downloader\Middleware\RequestMiddlewareInterface::class)
        ->tag('roach_php.request_middleware')
    ;

};
