<div align="center"><img src="logo.svg" alt="roach-php-bundle"/></div>

# roach-php-bundle
Symfony bundle for [Roach PHP](https://roach-php.dev).

[![Latest Stable Version](https://poser.pugx.org/nelexa/roach-php-bundle/v)](https://packagist.org/packages/nelexa/roach-php-bundle) [![PHP Version Require](https://poser.pugx.org/nelexa/roach-php-bundle/require/php)](https://packagist.org/packages/nelexa/roach-php-bundle)
[![Tests](https://github.com/Ne-Lexa/roach-php-bundle/actions/workflows/build.yml/badge.svg)](https://github.com/Ne-Lexa/roach-php-bundle/actions/workflows/build.yml)
[![Build Status](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/badges/build.png?b=main)](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/build-status/main)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/badges/quality-score.png?b=main)](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/?branch=main)
[![Code Coverage](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/badges/coverage.png?b=main)](https://scrutinizer-ci.com/g/Ne-Lexa/roach-php-bundle/?branch=main)
[![License](https://poser.pugx.org/nelexa/roach-php-bundle/license)](https://packagist.org/packages/nelexa/roach-php-bundle)

> Roach is a complete web scraping toolkit for PHP. It is ~~a shameless clone~~ heavily inspired by the popular Scrapy package for Python.

The Symfony bundle mostly provides the necessary container bindings for the various services Roach uses, as well as making certain configuration options available via a config file. To learn about how to actually start using Roach itself, check out the [rest of the documentation](https://roach-php.dev/docs/spiders).

## Installing the Symfony bundle
Add `nelexa/roach-php-bundle` to your composer.json file:
```bash
composer require nelexa/roach-php-bundle
```

#### Register the bundle:
Register bundle into config/bundles.php (Flex did it automatically):
```php
return [
    //...
    \Nelexa\RoachPhpBundle\RoachPhpBundle::class => ['all' => true],
];
```

## Available Commands
The Symfony bundle of Roach registers a few console commands to make out development experience as pleasant as possible.

### Run spider
```bash
php bin/console roach:run
```
After that, you will get the entire list of available spiders.
```text

 Choose a spider class:
  [0] App\Spider\GoogleSpider
  [1] App\Spider\FacebookSpider
  [2] App\Spider\TwitterSpider

```
Simply select the desired spider (▼ or ▲) or enter its number and press Enter.

You can pass as the first argument the name spider class name to run or its alias.
For example, if you have a class `App\Spider\GoogleSpider`, then you can pass the following aliases: `GoogleSpider`, `google_spider` or `google`.
```bash
php bin/console roach:run google
```
Sometimes it is useful to override the number of concurrent requests and the pre-request delay. To do this, you can pass the `--concurrency` and `--delay` options.
```bash
php bin/console roach:php google --concurrency 8 --delay 2
```
These options override the `$concurrency` and `$requestDelay` public properties of your spider.

### Starting the REPL

Roach ships with an [interactive shell](https://roach-php.dev/docs/repl) (often called Read-Evaluate-Print-Loop, or Repl for short) which makes prototyping our spiders a breeze. We can use the provided `roach:shell` command to launch a new Repl session.
```bash
php bin/console roach:shell "https://roach-php.dev/docs/introduction"
```

### Generator classes
First install [`Symfony MakerBundle`](https://symfony.com/bundles/SymfonyMakerBundle/current/index.html).
```bash
composer require --dev symfony/maker-bundle
```

#### Create a new roach spider class
```bash
php bin/console make:roach:spider
```

#### Create a new roach extension class
```bash
php bin/console make:roach:extension
```

#### Create a new roach item processor class
```bash
php bin/console make:roach:item:processor
```

#### Create a new roach downloader request middleware class
```bash
php bin/console make:roach:middleware:downloader:request
```

#### Create a new roach downloader response middleware class
```bash
php bin/console make:roach:middleware:downloader:response
```

#### Create a new roach spider item middleware class
```bash
php bin/console make:roach:middleware:spider:item
```

#### Create a new roach spider request middleware class
```bash
php bin/console make:roach:middleware:spider:request
```

#### Create a new roach spider response middleware class
```bash
php bin/console make:roach:middleware:spider:response
```

## Screencast
[![asciicast](https://asciinema.org/a/naGrp60zyNVQakQxV0v0Vnwvc.svg)](https://asciinema.org/a/naGrp60zyNVQakQxV0v0Vnwvc)

## Credits
* [Ne-Lexa](https://github.com/Ne-Lexa)
* [All contributors](https://github.com/Ne-Lexa/roach-php-bundle/graphs/contributors)

## Changelog
Changes are documented in the [releases page](https://github.com/Ne-Lexa/roach-php-bundle/releases).

## License
The MIT License (MIT). Please see [LICENSE](LICENSE) for more information.
