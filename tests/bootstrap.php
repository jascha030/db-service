<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

if (is_dir($testDir = dirname(__DIR__) . '/test-output')) {
    $iterator = new FilesystemIterator($testDir);

    /** @var SplFileInfo $file */
    foreach ($iterator as $file) {
        @unlink($file->getRealPath());
    }

    rmdir($testDir);
}

if (! mkdir($testDir) && ! is_dir($testDir)) {
    throw new RuntimeException(sprintf('Directory "%s" was not created', $testDir));
}

define('TEST_OUTPUT_DIR', $testDir);
