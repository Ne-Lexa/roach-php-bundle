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

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

final class MakeSpider extends AbstractMaker
{
    public static function getCommandName(): string
    {
        return 'make:roach:spider';
    }

    public static function getCommandDescription(): string
    {
        return 'Create a new spider class';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'spider-class',
                InputArgument::OPTIONAL,
                sprintf(
                    'Choose a name for your spider class (e.g. <fg=yellow>%sSpider</>)',
                    Str::asClassName(Str::getRandomTerm())
                )
            )
            ->setHelp(file_get_contents(__DIR__ . '/../Resources/help/MakeSpider.txt'))
        ;
    }

    /**
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $spiderClassNameDetails = $generator->createClassNameDetails(
            (string) $input->getArgument('spider-class'),
            'Spider\\',
            'Spider'
        );

        $generator->generateClass(
            $spiderClassNameDetails->getFullName(),
            __DIR__ . '/../Resources/skeleton/Spider.tpl.php'
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new spider class and set start urls and write parse method!');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
