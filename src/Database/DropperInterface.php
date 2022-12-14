<?php

declare(strict_types=1);

namespace Jascha030\DB\Database;

interface DropperInterface
{
    /**
     * Method to drop mysql databases by database name.
     */
    public function dropDatabase(string $name): void;
}
