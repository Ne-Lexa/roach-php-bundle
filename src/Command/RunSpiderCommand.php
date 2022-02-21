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

namespace Nelexa\RoachPhpBundle\Command;

use RoachPHP\Roach;
use RoachPHP\Spider\Configuration\Overrides;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ServiceLocator;

final class RunSpiderCommand extends Command
{
    protected static $defaultName = 'roach:run';

    protected static $defaultDescription = 'Run the provided spider';

    /** @var array<class-string<\RoachPHP\Spider\SpiderInterface>, array<string>> */
    private array $spiderNames;

    public function __construct(private ServiceLocator $serviceLocator)
    {
        /** @var array<class-string<\RoachPHP\Spider\SpiderInterface>> $providedServices */
        $providedServices = $this->serviceLocator->getProvidedServices();
        $this->spiderNames = $this->buildSpiderNameAliases($providedServices);
        parent::__construct();
    }

    protected function configure(): void
    {
        $spiderArgDescription = "Spider class name\nSupport spiders:\n";

        foreach ($this->spiderNames as $className => $aliases) {
            $spiderArgDescription .= '[*] <comment>' . $className . '</comment> or aliases <info>'
                . implode('</info>, <info>', $aliases)
                . '</info>' . \PHP_EOL;
        }

        $this
            ->addArgument('spider', InputArgument::OPTIONAL, rtrim($spiderArgDescription))
            ->addOption('delay', 't', InputOption::VALUE_OPTIONAL, 'The delay (in seconds) between requests.')
            ->addOption('concurrency', 'p', InputOption::VALUE_OPTIONAL, 'The number of concurrent requests.')
        ;
    }

    /**
     * @param array<class-string<\RoachPHP\Spider\SpiderInterface>> $services
     *
     * @return array<class-string<\RoachPHP\Spider\SpiderInterface>, array<string>>
     */
    private function buildSpiderNameAliases(array $services): array
    {
        $aliasServices = [];

        foreach ($services as $className) {
            $aliases = [];

            if (($lastPosDelim = strrpos($className, '\\')) !== false) {
                $shortClassName = substr($className, $lastPosDelim + 1);
                $aliases[] = $shortClassName;
            } else {
                $shortClassName = $className;
            }

            $snakeCaseClass = strtolower(ltrim(preg_replace('/[A-Z]([A-Z](?![a-z]))*/', '_$0', $shortClassName), '_'));
            $aliases[] = $snakeCaseClass;

            if (preg_match('~^(.*?)_spider$~', $snakeCaseClass, $matches)) {
                $aliases[] = $matches[1];
            }

            $aliasServices[$className] = $aliases;
        }

        return $aliasServices;
    }

    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        $spiderName = $input->getArgument('spider');

        if ($spiderName === null) {
            $spiderName = $this->selectSpiderClassName(new SymfonyStyle($input, $output));
            $input->setArgument('spider', $spiderName);
        }
    }

    private function selectSpiderClassName(OutputStyle $io): string
    {
        return (string) $io->choice('Choose a spider class', array_values($this->serviceLocator->getProvidedServices()));
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $spiderName = $input->getArgument('spider');
        $spiderClassName = $this->findSpiderClass($spiderName);

        if ($spiderClassName === null) {
            \assert($spiderName !== null);
            $io = new SymfonyStyle($input, $output);
            $io->error('Unknown spider ' . $spiderName);

            return self::FAILURE;
        }

        $delay = $input->getOption('delay');

        if ($delay !== null) {
            $delay = max(0, (int) $delay);
        }

        $concurrency = $input->getOption('concurrency');

        if ($concurrency !== null) {
            $concurrency = max(1, (int) $concurrency);
        }

        $overrides = new Overrides(
            concurrency: $concurrency,
            requestDelay: $delay,
        );

        Roach::startSpider($spiderClassName, $overrides);

        return self::SUCCESS;
    }

    /**
     * @return class-string<\RoachPHP\Spider\SpiderInterface>|null
     */
    private function findSpiderClass(?string $spiderName): ?string
    {
        if ($spiderName !== null) {
            foreach ($this->spiderNames as $className => $aliases) {
                if ($className === $spiderName || \in_array($spiderName, $aliases, true)) {
                    return $className;
                }
            }
        }

        return null;
    }
}
