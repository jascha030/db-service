<?php

declare(strict_types=1);

namespace Jascha030\DB;

use Doctrine\DBAL\Connection;
use Jascha030\DB\Database\ConnectorInterface;
use Jascha030\DB\Database\DropperInterface;
use Jascha030\DB\Database\FactoryInterface;

/**
 * Connection on a server-level.
 *
 * Responsible for creating and dropping databases.
 */
abstract class ManagerAbstract implements ConnectorInterface, FactoryInterface, DropperInterface
{
    /**
     * {@inheritDoc}
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function dropDatabase(string $name): void
    {
        $this->getConnection()
            ->createSchemaManager()
            ->dropDatabase($name);

        $this->getConnection()->close();
    }

    /**
     * {@inheritDoc}
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function createDatabase(string $name): void
    {
        $this->getConnection()
            ->createSchemaManager()
            ->createDatabase($name);

        $this->getConnection()->close();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    abstract public function getConnection(): Connection;
}
