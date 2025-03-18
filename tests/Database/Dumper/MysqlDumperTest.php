<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\Exception\InvalidArgumentException;
use Jascha030\DB\Exception\SystemDependencyException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;

use function defined;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertFileExists;

use const TEST_OUTPUT_DIR;

/**
 * @covers \Jascha030\DB\Database\Dumper\DumperAbstract
 * @covers \Jascha030\DB\Database\Dumper\MysqlDumper
 *
 * @internal
 */
class MysqlDumperTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws SystemDependencyException
     * @throws Exception
     */
    public function testDump(): void
    {
        if (! defined('TEST_OUTPUT_DIR')) {
            static::fail('Output dir was not created.');
        }

        if (! isset($_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DUMPER_TEST_DATABASE'])) {
            static::fail('Could not find required credentials in environment.');
        }

        $connection = DriverManager::getConnection([ // @phpstan-ignore-line
            'user'     => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASSWORD'],
            'host'     => 'localhost',
            'driver'   => 'pdo_mysql',
        ]);

        /**
         * @var string $db
         */
        $db = $_ENV['DUMPER_TEST_DATABASE'];

        /**
         * @var string $db_user
         */
        $db_user = $_ENV['DB_USER'];

        /**
         * @var string $db_pass
         */
        $db_pass = $_ENV['DB_PASSWORD'];

        /**
         * @var string $db_name
         */
        $db_name = $_ENV['DUMPER_TEST_DATABASE'];

        $manager = $connection->createSchemaManager();
        $manager->createDatabase($db);

        $dumper   = new MysqlDumper();
        $expected = TEST_OUTPUT_DIR . '/' . $_ENV['DUMPER_TEST_DATABASE'] . '.sql'; // @phpstan-ignore-line

        assertFileDoesNotExist($expected);

        assertEquals(
            Command::SUCCESS,
            $dumper->dump(
                [
                    'user'     => $db_user,
                    'password' => $db_pass,
                    'dbname'   => $db_name,
                ],
                TEST_OUTPUT_DIR,
                new ConsoleOutput()
            )
        );

        assertFileExists($expected);

        $manager->dropDatabase($db_name);
    }
}
