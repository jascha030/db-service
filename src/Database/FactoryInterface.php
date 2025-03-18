<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
