<?php

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
use Symfony\Component\Console\Input\InputOption;

/**
 * @method string getCommandDescription()
 */
class SpiderMake extends AbstractMaker
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
            ->addArgument('spider-class', InputArgument::OPTIONAL, sprintf('Choose a name for your spider class (e.g. <fg=yellow>%sSpider</>)', Str::asClassName(Str::getRandomTerm())))
            ->setHelp(file_get_contents(__DIR__.'/../Resources/help/MakeSpider.txt'))
        ;
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $spiderClassNameDetails = $generator->createClassNameDetails(
            $input->getArgument('spider-class'),
            'Spider\\',
            'Spider'
        );

        $noTemplate = $input->getOption('no-template');
        $generator->generateController(
            $spiderClassNameDetails->getFullName(),
            'Spider.tpl.php',
            [
                'route_path' => Str::asRoutePath($spiderClassNameDetails->getRelativeNameWithoutSuffix()),
                'route_name' => Str::asRouteName($spiderClassNameDetails->getRelativeNameWithoutSuffix()),
                'with_template' => $this->isTwigInstalled() && !$noTemplate,
                'template_name' => $templateName,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);
        $io->text('Next: Open your new spider class and set start urls and write parse method!');
    }

    public function configureDependencies(DependencyBuilder $dependencies): void
    {
    }
}
