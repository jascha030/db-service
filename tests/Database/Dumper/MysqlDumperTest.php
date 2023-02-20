<?php

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

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
     */
    public function testDump(): void
    {
        if (! defined('TEST_OUTPUT_DIR')) {
            static::fail('Output dir was not created.');
        }

        if (! isset($_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DUMPER_TEST_DATABASE'])) {
            static::fail('Could not find required credentials in environment.');
        }

        $dumper   = new MysqlDumper();
        $expected = TEST_OUTPUT_DIR . '/' . $_ENV['DUMPER_TEST_DATABASE'] . '.sql';

        assertFileDoesNotExist($expected);

        assertEquals(
            Command::SUCCESS,
            $dumper->dump(
                [
                    'user'     => (string) $_ENV['DB_USER'],
                    'password' => (string) $_ENV['DB_PASSWORD'],
                    'dbname'   => (string) $_ENV['DUMPER_TEST_DATABASE'],
                ],
                TEST_OUTPUT_DIR,
                new ConsoleOutput()
            )
        );

        assertFileExists($expected);
    }
}
