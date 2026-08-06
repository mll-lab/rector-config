<?php declare(strict_types=1);

use MLL\RectorConfig\Rector\PrimaryKeyColumnToIDAliasRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(PrimaryKeyColumnToIDAliasRector::class);
};
