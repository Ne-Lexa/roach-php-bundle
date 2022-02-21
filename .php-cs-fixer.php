<?php

declare(strict_types=1);

/*
 * PHP Code Style Fixer (config created for version 3.6.0 (Roe Deer)).
 *
 * Use one of the following console commands to just see the
 * changes that will be made.
 * - `php-cs-fixer fix --config='.php-cs-fixer.php' --dry-run`
 * - `php '.php-cs-fixer.php'`
 * - `php7.1 '.php-cs-fixer.php'`
 * - `php7.2 '.php-cs-fixer.php'`
 * - `php7.3 '.php-cs-fixer.php'`
 * - `php7.4 '.php-cs-fixer.php'`
 * - `php8.0 '.php-cs-fixer.php'`
 *
 * Use one of the following console commands to fix PHP code:
 * - `php-cs-fixer fix --config='.php-cs-fixer.php'
 * - `php '.php-cs-fixer.php' --force`
 * - `php7.1 '.php-cs-fixer.php' --force`
 * - `php7.2 '.php-cs-fixer.php' --force`
 * - `php7.3 '.php-cs-fixer.php' --force`
 * - `php7.4 '.php-cs-fixer.php' --force`
 * - `php8.0 '.php-cs-fixer.php' --force`
 *
 * @see https://cs.symfony.com/
 */
$rules = [
    // PHP code MUST use only UTF-8 without BOM (remove BOM).
    'encoding' => true,
];

if (\PHP_SAPI === 'cli' && !class_exists(\PhpCsFixer\Config::class)) {
    $which = static function ($program, $default = null) {
        exec(sprintf('command -v %s', escapeshellarg($program)), $output, $resultCode);
        if ($resultCode === 0) {
            return trim($output[0]);
        }

        return $default;
    };
    $findExistsFile = static function (array $files): ?string {
        foreach ($files as $file) {
            if ($file !== null && is_file($file)) {
                return $file;
            }
        }

        return null;
    };

    $fixerBinaries = [
        __DIR__ . '/vendor/bin/php-cs-fixer',
        __DIR__ . '/tools/php-cs-fixer/vendor/bin/php-cs-fixer',
        $which('php-cs-fixer'),
        isset($_SERVER['COMPOSER_HOME']) ? $_SERVER['COMPOSER_HOME'] . '/vendor/bin/php-cs-fixer' : null,
    ];
    $fixerBin = $findExistsFile($fixerBinaries) ?? 'php-cs-fixer';
    $phpBin = $_SERVER['_'] ?? 'php';

    $dryRun = !in_array('--force', $_SERVER['argv'], true);
    $commandFormat = '%s %s fix --config %s --diff --ansi -vv%s';
    $command = sprintf(
        $commandFormat,
        escapeshellarg($phpBin),
        escapeshellarg($fixerBin),
        escapeshellarg(__FILE__),
        $dryRun ? ' --dry-run' : ''
    );
    $outputCommand = sprintf(
        $commandFormat,
        $phpBin,
        strpos($fixerBin, ' ') === false ? $fixerBin : escapeshellarg($fixerBin),
        escapeshellarg(__FILE__),
        $dryRun ? ' --dry-run' : ''
    );

    fwrite(\STDOUT, "\e[22;94m" . $outputCommand . "\e[m\n\n");
    system($command, $returnCode);

    if ($dryRun || $returnCode === 8) {
        fwrite(\STDOUT, "\n\e[1;40;93m\e[K\n");
        fwrite(\STDOUT, "    [DEBUG] Dry run php-cs-fixer config.\e[K\n");
        fwrite(\STDOUT, "            Only shows which files would have been modified.\e[K\n");
        fwrite(\STDOUT, "            To apply the rules, use the --force option:\e[K\n\e[K\n");
        fwrite(
            \STDOUT,
            sprintf(
                "            \e[1;40;92m%s %s --force\e[K\n\e[0m\n",
                basename($phpBin),
                $_SERVER['argv'][0]
            )
        );
    } elseif ($returnCode !== 0) {
        fwrite(\STDERR, sprintf("\n\e[1;41;97m\e[K\n    ERROR CODE: %s\e[K\n\e[0m\n", $returnCode));
    }

    exit($returnCode);
}

return (new \PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setCacheFile('./.php-cs-fixer.cache')
    ->setRules($rules)
    ->setRiskyAllowed(true)
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->ignoreUnreadableDirs()
            ->in(__DIR__)
    );
