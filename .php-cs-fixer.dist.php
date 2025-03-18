<?php

/*
 * Copyright (c) 2022 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Jascha030\PhpCsFixer\Config;
use PhpCsFixer\Finder;

require_once __DIR__ . '/vendor-bin/php-cs-fixer/vendor/autoload.php';

/**
 * Cache dir and file location.
 */
$cacheDirectory = __DIR__ . '/.var/cache';
$cacheFile      = "{$cacheDirectory}/.php-cs-fixer.cache";

/**
 * Create a .cache dir if not already present.
 */
if (! file_exists($cacheDirectory) && ! mkdir($cacheDirectory, 0o700, true) && ! is_dir($cacheDirectory)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', $cacheDirectory));
}

$finder = Finder::create()
    ->in(__DIR__)
    ->exclude([
        '.idea',
        '.phive',
        '.var',
        'tests/Fixtures',
        'tools',
        'vendor',
        'vendor-bin',
        'test-output',
    ])
    ->ignoreDotFiles(true);

return (new Config(
    Config::PHP_82,
    <<<'EOF'
        Copyright (c) 2025 Jascha van Aalst

        For the full copyright and license information, please view the LICENSE
        file that was distributed with this source code.
        EOF,
))
    ->setFinder($finder)
    ->setCacheFile($cacheFile);
