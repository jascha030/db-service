<?php

declare(strict_types=1);

namespace Jascha030\DB\Database;

use Doctrine\DBAL\Connection;

interface ConnectorInterface
{
    public function getConnection(): Connection;
}
