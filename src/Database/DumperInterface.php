<?php

declare(strict_types=1);

namespace Jascha030\DB\Database;

use Symfony\Component\Console\Output\OutputInterface;

interface DumperInterface
{
    /**
     * @param array<string|string> $options user input
     * @param null|string          $path    user provided path, if null `getcwd()` will be used
     * @param null|OutputInterface $output  optionally provide instance of `OutputInterface` to passthrough output of
     *                                      an internally executed command or for usage within CLI context
     *
     * @return bbol|int bool or int to indicate wether the database was successfully dumped, provide exit status when using in CLI Context
     */
    public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int|bool;
}
