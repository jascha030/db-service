<?php

declare(strict_types=1);

namespace Jascha030\DB\Exception;

use Exception;
use function sprintf;

class SystemDependencyException extends Exception
{
    public function __construct(string $dependency, ?string $url = null, int $code = 0, ?Throwable $previous = null)
    {
        $instructions = $url !== null
            ? sprintf('%sGo to %s for instructions on how to install %s', PHP_EOL, $url, $dependency)
            : '';

        parent::__construct(
            sprintf(
                'Dependency: "%s" is required and could not be located on the current system.%s',
                $dependency,
                $instructions
            ),
            $code,
            $previous
        );
    }
}
