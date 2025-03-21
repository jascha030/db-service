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
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertInstanceOf;

/**
 * @covers \Jascha030\DB\ManagerAbstract
 *
 * @internal
 */
final class ManagerAbstractTest extends TestCase
{
    private static Connection $connection;

    private static string $databaseName;

    /**
     * @throws Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$connection = DriverManager::getConnection([ // @phpstan-ignore-line
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ]);

        self::$databaseName = uniqid('unittest', false);
    }

    private function getConnection(): Connection
    {
        return self::$connection;
    }

    /**
     * @throws Exception
     */
    public function testCreateDatabase(): void
    {
        $schemaManager = $this->getConnection()->createSchemaManager();

        // First assert our database is not present beforehand.
        assertArrayNotHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));

        $this->getManager()->createDatabase(self::$databaseName);

        // Assert our database is present after creation.
        assertArrayHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));
    }

    /**
     * @depends testCreateDatabase
     *
     * @throws Exception
     */
    public function testDropDatabase(): void
    {
        $schemaManager = $this->getConnection()->createSchemaManager();

        // First assert our database IS present beforehand.
        self::assertArrayHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));

        $this->getManager()->dropDatabase(self::$databaseName);

        // Assert our database is absent after deletion.
        self::assertArrayNotHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));
    }

    public function testGetConnection(): void
    {
        assertInstanceOf(Connection::class, $this->getManager()->getConnection()); // @phpstan-ignore-line
    }

    /**
     * @return ManagerAbstract|(ManagerAbstract&MockObject)|MockObject
     */
    private function getManager()
    {
        $mock = $this->getMockForAbstractClass(ManagerAbstract::class);
        $mock->method('getConnection')->willReturn(self::$connection);

        return $mock;
    }
}
