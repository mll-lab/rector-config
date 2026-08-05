<?php declare(strict_types=1);

use MLL\RectorConfig\Rector\PrimaryKeyColumnToIdAliasRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PrimaryKeyColumnToIdAliasRector::class);
};
