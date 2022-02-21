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
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker as BaseAbstractMaker;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class AbstractMaker extends BaseAbstractMaker
{
    abstract protected function getSuffix(): string;

    abstract protected function getNamespace(): string;

    abstract protected function getTemplateFilename(): string;

    public static function getCommandDescription(): string
    {
        return sprintf('Create a new roach %s class', static::getDescriptionClass());
    }

    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command
            ->addArgument(
                'class',
                InputArgument::OPTIONAL,
                $this->getArgumentChooseClassDescription()
            )
            ->setHelp($this->getArgumentChooseClassHelp())
        ;
    }

    /**
     * @throws \Exception
     */
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $classNameDetails = $generator->createClassNameDetails(
            (string) $input->getArgument('class'),
            $this->getNamespace(),
            $this->getSuffix()
        );

        $generator->generateClass(
            $classNameDetails->getFullName(),
            $this->getTemplateFilename()
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text($this->getAfterSuccessMessage());
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }

    protected function getArgumentChooseClassDescription(): string
    {
        return sprintf(
            'Choose a name for your ' . $this->getSuffix() . ' class (e.g. <fg=yellow>%s%s</>)',
            Str::asClassName(Str::getRandomTerm()),
            $this->getSuffix(),
        );
    }

    abstract protected static function getDescriptionClass(): string;

    protected function getArgumentChooseClassHelp(): string
    {
        $generateName = static::getDescriptionClass();

        return sprintf(
            'The <info>%%command.name%%</info> command generates a new roach %s class.

<info>php %%command.full_name%% CoolStuff%s</info>

If the argument is missing, the command will ask for the %s class name interactively.',
            $generateName,
            $this->getSuffix(),
            $generateName
        );
    }

    protected function getAfterSuccessMessage(): string
    {
        return sprintf('Next: Open your new %s class and write your logic!', static::getDescriptionClass());
    }
}
