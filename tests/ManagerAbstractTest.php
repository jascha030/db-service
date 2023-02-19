<?php
/** @noinspection PhpMissingParentCallCommonInspection */

declare(strict_types=1);

namespace Jascha030\DB;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertArrayNotHasKey;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertArrayHasKey;

/**
 * @covers \Jascha030\DB\ManagerAbstract
 *
 * @internal
 */
final class ManagerAbstractTest extends TestCase
{
    private static ?Connection $connection = null;

    private static ?string $databaseName = null;

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public static function setUpBeforeClass(): void
    {
        self::$connection = DriverManager::getConnection([
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ]);

        self::$databaseName = uniqid('unittest', false);
    }

    public static function tearDownAfterClass(): void
    {
        self::$connection   = null;
        self::$databaseName = null;
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function testCreateDatabase(): void
    {
        self::$connection->connect();

        $schemaManager = self::$connection->createSchemaManager();

        // First assert our database is not present beforehand.
        assertArrayNotHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));

        $this->getManager()->createDatabase(self::$databaseName);

        // Assert our database is present after creation.
        assertArrayHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));
    }

    /**
     * @depends testCreateDatabase
     *
     * @throws \Doctrine\DBAL\Exception
     */
    public function testDropDatabase(): void
    {
        self::$connection->connect();

        $schemaManager = self::$connection->createSchemaManager();

        // First assert our database IS present beforehand.
        $this->assertArrayHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));

        $this->getManager()->dropDatabase(self::$databaseName);

        // Assert our database is absent after deletion.
        $this->assertArrayNotHasKey(self::$databaseName, array_flip($schemaManager->listDatabases()));
    }

    public function testGetConnection(): void
    {
        assertInstanceOf(Connection::class, $this->getManager()->getConnection());
    }

    private function getManager()
    {
        $mock = $this->getMockForAbstractClass(ManagerAbstract::class);
        $mock->method('getConnection')->willReturn(self::$connection);

        return $mock;
    }
}
