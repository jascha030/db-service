<?php

declare(strict_types=1);

use Dotenv\Dotenv;

(static function () {
    require dirname(__DIR__) . '/vendor/autoload.php';

    Dotenv::createImmutable(__DIR__)->load();
})();
