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

/** @var \Composer\Autoload\ClassLoader|null $loader */
$loader = include __DIR__ . '/../vendor/autoload.php';

if (!$loader) {
    echo <<<'EOT'
        You need to install the project dependencies using Composer:
        $ wget https://getcomposer.org/composer.phar
        OR
        $ curl -s https://getcomposer.org/installer | php
        $ php composer.phar install --dev
        $ phpunit
        EOT;

    exit(1);
}
