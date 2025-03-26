<?php declare(strict_types=1);

namespace MLL\RectorConfig;

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Transform\Rector\FuncCall\FuncCallToNewRector;
use Rector\Transform\Rector\StaticCall\StaticCallToNewRector;
use Rector\Transform\ValueObject\StaticCallToNew;

/** Configure rector with PHP rules. */
function config(RectorConfig $rectorConfig): void
{
    $rectorConfig->rule(FirstClassCallableRector::class);
}

/** Configure rector with Laravel rules. */
function laravel(RectorConfig $rectorConfig): void
{
    config($rectorConfig);
    $rectorConfig->ruleWithConfiguration(FuncCallToNewRector::class, [
        'collect' => \Illuminate\Support\Collection::class,
    ]);
    $rectorConfig->ruleWithConfiguration(StaticCallToNewRector::class, [
        new StaticCallToNew(
            class: \Illuminate\Support\Collection::class,
            method: 'make',
        ),
    ]);
}
