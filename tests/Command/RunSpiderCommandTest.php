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

namespace Nelexa\RoachPhpBundle\Tests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 *
 * @medium
 */
class RunSpiderCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('roach:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'spider' => 'quotes',
        ]);

        $commandTester->assertCommandIsSuccessful();
    }

    public function testUnknownSpider(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('roach:run');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'spider' => 'unknown_spider',
        ]);

        static::assertNotSame(0, $commandTester->getStatusCode());
        static::assertStringContainsString('[ERROR] Unknown spider unknown_spider', $commandTester->getDisplay());
    }
}
