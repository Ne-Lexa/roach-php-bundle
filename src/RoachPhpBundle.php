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

namespace Nelexa\RoachPhpBundle;

use Nelexa\RoachPhpBundle\DependencyInjection\Compiler\RoachCompilerPass;
use RoachPHP\Roach;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RoachPhpBundle extends Bundle
{
    public function boot(): void
    {
        parent::boot();
        Roach::useContainer($this->container);
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new RoachCompilerPass());
    }
}
