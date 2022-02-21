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

namespace Nelexa\RoachPhpBundle\Maker;

final class MakeItemProcessor extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:item:processor';
    }

    protected static function getDescriptionClass(): string
    {
        return 'item processor';
    }

    protected function getSuffix(): string
    {
        return 'ItemProcessor';
    }

    protected function getNamespace(): string
    {
        return 'Spider\\ItemProcessor\\';
    }

    protected function getTemplateFilename(): string
    {
        return __DIR__ . '/../Resources/skeleton/ItemProcessor.tpl.php';
    }
}
