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

final class MakeSpider extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:spider';
    }

    protected static function getDescriptionClass(): string
    {
        return 'spider';
    }

    protected function getSuffix(): string
    {
        return 'Spider';
    }

    protected function getNamespace(): string
    {
        return 'Spider\\';
    }

    protected function getTemplateFilename(): string
    {
        return __DIR__ . '/../Resources/skeleton/Spider.tpl.php';
    }

    protected function getAfterSuccessMessage(): string
    {
        return sprintf(
            'Next: Open your new %s class and set start urls and write parse method!',
            self::getDescriptionClass()
        );
    }
}
