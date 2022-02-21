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
 * @small
 */
class MakeCommandTest extends KernelTestCase
{
    /**
     * @dataProvider provideMakeCommand
     */
    public function testMakeCommand(
        string $commandName,
        array $input,
        string $outputPath
    ): void {
        $outputFilename = __DIR__ . '/../' . $outputPath;

        try {
            $kernel = self::bootKernel();
            $application = new Application($kernel);

            $command = $application->find($commandName);
            $commandTester = new CommandTester($command);
            $commandTester->execute($input);

            $commandTester->assertCommandIsSuccessful();

            static::assertStringContainsString($outputPath, $commandTester->getDisplay());
            static::assertFileExists($outputFilename);
        } finally {
            $this->cleanDirectory($outputFilename);
        }
    }

    public function provideMakeCommand(): iterable
    {
        yield 'make:roach:spider' => [
            'make:roach:spider',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/CustomSpider.php',
        ];

        yield 'make:roach:extension' => [
            'make:roach:extension',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Extension/CustomExtension.php',
        ];

        yield 'make:roach:item:processor' => [
            'make:roach:item:processor',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/ItemProcessor/CustomItemProcessor.php',
        ];

        yield 'make:roach:middleware:downloader:request' => [
            'make:roach:middleware:downloader:request',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Middleware/Downloader/CustomRequestMiddleware.php',
        ];

        yield 'make:roach:middleware:downloader:response' => [
            'make:roach:middleware:downloader:response',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Middleware/Downloader/CustomResponseMiddleware.php',
        ];

        yield 'make:roach:middleware:spider:request' => [
            'make:roach:middleware:spider:request',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Middleware/Spider/CustomRequestMiddleware.php',
        ];

        yield 'make:roach:middleware:spider:response' => [
            'make:roach:middleware:spider:response',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Middleware/Spider/CustomResponseMiddleware.php',
        ];

        yield 'make:roach:middleware:spider:item' => [
            'make:roach:middleware:spider:item',
            [
                'class' => 'Custom',
                '--no-interaction' => true,
                '-v' => true,
            ],
            'App/Spider/Middleware/Spider/CustomItemMiddleware.php',
        ];
    }

    private function cleanDirectory(string $outputFilename): void
    {
        if (is_file($outputFilename)) {
            unlink($outputFilename);

            $path = $outputFilename;

            while (true) {
                $path = \dirname($path);
                $di = new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS);

                if (iterator_count($di) !== 0) {
                    break;
                }
                rmdir($path);
            }
        }
    }
}
