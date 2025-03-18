<?php

/*
 * Copyright (c) 2025 Jascha van Aalst
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Jascha030\DB\Exception;

use Exception;
use Throwable;

use function sprintf;

use const PHP_EOL;

class SystemDependencyException extends Exception
{
    public function __construct(string $dependency, ?string $url = null, int $code = 0, ?Throwable $previous = null)
    {
        $instructions = null !== $url
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
