<?php

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

use Doctrine\DBAL\Exception\InvalidArgumentException;
use Jascha030\DB\Database\DumperInterface;
use Jascha030\DB\Exception\SystemDependencyException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;
use function array_merge;
use function count;
use function getcwd;
use function implode;
use function is_dir;
use function sprintf;
use function str_ends_with;

/**
 * Dumper implementation for MySQL using mysqldump.
 *
 * @implements DumperInterface
 *
 * @see  DumperInterface
 * @link 'https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html
 */
final class MysqlDumper implements DumperInterface
{
    /**
     * @throws SystemDependencyException In case the mysqldump binary could not be located.
     * @throws InvalidArgumentException
     */
    public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int
    {
        $required = $this->resolveParameters($options, ['dbname' => 'database', 'user' => 'dbuser'], true);
        $optional = $this->resolveParameters($options, ['password' => 'pass']);

        if (count($required['errors']) > 0) {
            throw new InvalidArgumentException(sprintf(
                'Invalid argument "\$options", missing required key(s): %s',
                implode(', ', $required['errors'])
            ));
        }

        $values = array_merge($required['values'], $optional);

        if ($path === null) {
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

        $process = (new Process(
            [
                $this->getBinary(),
                sprintf('-u %s', $values['user']),
                sprintf('-p%s ', isset($values['password']) ? ' ' . $values['password'] : ''),
                $values['dbname'],
                sprintf('< %s', $path),
            ]
        ))->mustRun();

        $output?->writeln($process->getOutput());

        return $process->getExitCode();
    }

    /**
     * @throws SystemDependencyException
     */
    private function getBinary(): string
    {
        $finder = new ExecutableFinder();
        $binary = $finder->find('mysqldump');

        if ($binary === null) {
            throw new SystemDependencyException(
                'mysqldump',
                'https://dev.mysql.com/doc/refman/8.0/en/mysqldump.html'
            );
        }

        return $binary;
    }

    /**
     * @param array<string|string> $options user input.
     * @param array<string,string> $keys keys and possible aliases to retrieve in $options.
     *
     * @return array<string,string|string[]> Values and errors in case required keys are not provided.
     */
    private function resolveParameters(array $options, array $keys, bool $required = false): array
    {
        $values = [];
        $errors = [];

        foreach ($keys as $key => $alt) {
            $value = $options[$key] ?? $options[$alt] ?? null;

            if (null !== $value) {
                $values[$key] = $value;

                continue;
            }

            if ($required) {
                $errors = $key;
            }
        }

        return $required
            ? compact('values', 'errors')
            : $values;
    }
}
