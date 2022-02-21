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

namespace Nelexa\RoachPhpBundle\Maker\Downloader;

use Nelexa\RoachPhpBundle\Maker\AbstractMaker;

final class MakeRequestMiddleware extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:downloader:middleware:request';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new roach downloader request middleware class';
    }

    protected function getSuffix(): string
    {
        return 'RequestMiddleware';
    }

    protected function getNamespace(): string
    {
        return 'Spider\\Middleware\\Downloader\\';
    }

    protected function getGenerateName(): string
    {
        return 'downloader request middleware';
    }

    protected function getTemplateFilename(): string
    {
        return __DIR__ . '/../../Resources/skeleton/Downloader/RequestMiddleware.tpl.php';
    }
}
