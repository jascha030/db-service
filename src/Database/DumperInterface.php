<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jascha030\DB\Database;

use Symfony\Component\Console\Output\OutputInterface;

interface DumperInterface
{
    /**
     * @param array<int|string,string|string[]> $options $options user input
     * @param string|null                       $path    user provided path, if null `getcwd()` will be used
     * @param OutputInterface|null              $output  optionally provide instance of `OutputInterface` to
     *                                                   passthrough output of an internally executed command or for
     *                                                   usage within CLI context
     *
     * @return bool|int bool or int to indicate wether the database was successfully dumped, provide exit status when
     *                  using in CLI Context
     */
    public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int|bool;
}
