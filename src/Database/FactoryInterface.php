<?php

declare(strict_types=1);

namespace Jascha030\DB\Database;

use Doctrine\DBAL\Exception;

interface FactoryInterface
{
    /**
     * Create a mysql database.
     *
     * @throws Exception
     */
    public function createDatabase(string $name): void;
}
