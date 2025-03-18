<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jascha030\DB;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

trait TestConnectionTrait
{
    /**
     * @throws Exception
     */
    private function getConnection(): Connection
    {
        return DriverManager::getConnection([
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ]);
    }

    /**
     * @throws Exception
     */
    public function getSchemaManager(): AbstractSchemaManager
    {
        return $this->getConnection()->createSchemaManager();
    }

    /**
     * @throws Exception
     */
    public function setupTestDB(string $name): void
    {
        $this->deleteTestDB($name);

        $manager = $this->getSchemaManager();
        $manager->createDatabase($name);
    }

    /**
     * @throws Exception
     */
    public function deleteTestDB(string $name): void
    {
        $manager = $this->getSchemaManager();
        $manager->dropDatabase($name);
    }
}
