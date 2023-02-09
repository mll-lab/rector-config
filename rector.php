<?php declare(strict_types=1);

use Rector\Config\RectorConfig;

use function MLL\RectorConfig\config;

return static function (RectorConfig $rectorConfig): void {
    config($rectorConfig);

    $rectorConfig->paths([
        __DIR__ . '/.php-cs-fixer.php',
        __DIR__ . '/config.php',
        __DIR__ . '/rector.php',
    ]);
};
