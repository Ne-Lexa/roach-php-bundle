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

namespace Nelexa\RoachPhpBundle\Maker\Spider;

use Nelexa\RoachPhpBundle\Maker\AbstractMaker;

final class MakeRequestMiddleware extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:middleware:spider:request';
    }

    protected static function getDescriptionClass(): string
    {
        return 'spider request middleware';
    }

    protected function getSuffix(): string
    {
        return 'RequestMiddleware';
    }

    protected function getNamespace(): string
    {
        return 'Spider\\Middleware\\Spider\\';
    }

    protected function getTemplateFilename(): string
    {
        return __DIR__ . '/../../Resources/skeleton/Spider/RequestMiddleware.tpl.php';
    }
}
