<?php declare(strict_types=1);

use MLL\RectorConfig\Rector\IfThrowToCoalesceThrowRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(IfThrowToCoalesceThrowRector::class);
};
