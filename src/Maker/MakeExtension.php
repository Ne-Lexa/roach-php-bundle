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

final class MakeExtension extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:extension';
    }

    protected static function getDescriptionClass(): string
    {
        return 'extension';
    }

    protected function getSuffix(): string
    {
        return 'Extension';
    }

    protected function getNamespace(): string
    {
        return 'Spider\\Extension\\';
    }

    protected function getTemplateFilename(): string
    {
        return __DIR__ . '/../Resources/skeleton/Extension.tpl.php';
    }
}
