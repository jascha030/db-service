<?php

declare(strict_types=1);

namespace Jascha030\DB\Database;

use Symfony\Component\Console\Output\OutputInterface;

interface DumperInterface
{
    public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int;
}
