<?php

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Jascha030\DB\Exception\SystemDependencyException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\ConsoleOutput;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFileDoesNotExist;
use function PHPUnit\Framework\assertFileExists;

/**
 * @covers \Jascha030\DB\Database\Dumper\DumperAbstract
 * @covers \Jascha030\DB\Database\Dumper\MysqlDumper
 */
class MysqlDumperTest extends TestCase
{
    /**
     * @throws InvalidArgumentException
     * @throws SystemDependencyException
     */
    public function testDump(): void
    {
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
                    'user'     => $_ENV['DB_USER'],
                    'password' => $_ENV['DB_PASSWORD'],
                    'dbname'   => $_ENV['DUMPER_TEST_DATABASE'],
                ],
                TEST_OUTPUT_DIR,
                new ConsoleOutput()
            )
        );

        assertFileExists($expected);
    }
}
