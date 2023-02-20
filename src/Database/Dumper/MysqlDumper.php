<?php /** @noinspection PhpUnnecessaryFullyQualifiedNameInspection */

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Jascha030\DB\Database\DumperInterface;
use Jascha030\DB\Exception\SystemDependencyException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use function count;
use function file_exists;
use function getcwd;
use function implode;
use function is_dir;
use function sleep;
use function sprintf;
use function str_ends_with;

/**
 * Dumper implementation for MySQL using mysqldump.
 *
 * @see  DumperAbstract
 * @see  DumperInterface
 * @link  https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html
 */
final class MysqlDumper extends DumperAbstract
{
    /**
     * {@inheritDoc}
     *
     * @throws SystemDependencyException in case the mysqldump binary could not be located
     * @throws InvalidArgumentException
     */
    public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int
    {
        $required = $this->resolveParameters($options);

        if (count($required['errors']) > 0) {
            throw new InvalidArgumentException(sprintf(
                'Invalid argument "\$options", missing required key(s): %s',
                implode(', ', $required['errors'])
            ));
        }

        /**
         * @var string[] $values
         */
        $values = $required['values'];

        if (null === $path) {
            $path = sprintf('./%s.sql', $values['dbname']);
        }

        if (! str_ends_with($path, '.sql')) {
            if (is_dir(getcwd() . '/' . $path)) {
                $path = getcwd() . '/' . $path;
            }

            if (! is_dir($path)) {
                throw new \InvalidArgumentException(sprintf('Invalid path: "%s" provided', $path));
            }

            $path = sprintf('%s/%s.sql', $path, $values['dbname']);
        }

        $command = Process::fromShellCommandline(implode(' ', [
            $this->getBinary(),
            '--add-drop-table',
            '--skip-comments',
            '--default-character-set=utf8mb4',
            '--user="${:USER}"',
            '--password="${:PASSWORD}"',
            '"${:DATABASE}"',
            '--result-file="${:OUTPUT_FILE}"',
        ]));

        $status = $command->run(
            static function (string $type, string $line) use ($output) {
                $style = Process::ERR === $type
                    ? 'error'
                    : 'info';

                $output?->writeln(sprintf('<%1$s>%2$s</%1$s>', $style, $line));
            },
            [
                'USER'        => $values['user'],
                'PASSWORD'    => $values['password'],
                'DATABASE'    => $values['dbname'],
                'OUTPUT_FILE' => $path,
            ]
        );

        if (Command::SUCCESS !== $status) {
            return $status;
        }

        while (! file_exists($path)) {
            sleep(1);
        }

        return $status;
    }

    /**
     * @throws SystemDependencyException
     */
    private function getBinary(): string
    {
        $finder = new ExecutableFinder();
        $binary = $finder->find('mysqldump');

        if (null === $binary) {
            throw new SystemDependencyException(
                'mysqldump',
                'https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html'
            );
        }

        return $binary;
    }

    /**
     * {@inheritDoc}
     *
     * @return string[]
     */
    protected function getRequiredOptions(): array
    {
        return ['dbname', 'user', 'password'];
    }
}
