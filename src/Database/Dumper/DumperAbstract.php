<?php

declare(strict_types=1);

namespace Jascha030\DB\Database\Dumper;

use Jascha030\DB\Database\DumperInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class DumperAbstract implements DumperInterface
{
    /**
     * {@inheritDoc}
     */
    abstract public function dump(array $options, ?string $path = null, ?OutputInterface $output = null): int;

    /**
     * Get the options that are required in the $options argument of the dump method.
     *
     * @return array<string> required keys to retrieve from user provided $options
     *
     * @see static::dump()
     */
    abstract protected function getRequiredOptions(): array;

    /**
     * @param array<string> $options user input
     *
     * @return array<string,string|string[]> values and errors in case required keys are not provided
     */
    protected function resolveParameters(array $options): array
    {
        [$values, $errors] = [[], []];

        foreach ($this->getRequiredOptions() as $key) {
            if (null === ($options[$key] ?? null)) {
                $errors[] = $key;
            }

            $values[$key] = $options[$key];
        }

        return ['values' => array_filter($values), 'errors' => $errors];
    }
}
