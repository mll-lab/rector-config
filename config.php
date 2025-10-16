<?php declare(strict_types=1);

namespace MLL\RectorConfig;

use Rector\Config\RectorConfig;

/** Configure rector with PHP rules. */
function config(RectorConfig $rectorConfig): void
{
    $rectorConfig->rule(\Rector\Php81\Rector\Array_\FirstClassCallableRector::class);
}

/** Configure rector with Laravel rules. */
function laravel(RectorConfig $rectorConfig): void
{
    config($rectorConfig);
    $rectorConfig->ruleWithConfiguration(\Rector\Transform\Rector\FuncCall\FuncCallToNewRector::class, [
        'collect' => \Illuminate\Support\Collection::class,
    ]);
    $rectorConfig->ruleWithConfiguration(\Rector\Transform\Rector\StaticCall\StaticCallToNewRector::class, [
        new \Rector\Transform\ValueObject\StaticCallToNew(
            class: \Illuminate\Support\Collection::class,
            method: 'make',
        ),
    ]);
}
