<?php declare(strict_types=1);

namespace MLL\RectorConfig;

use Rector\Config\RectorConfig;

/**
 * Configure rector with PHP rules.
 */
function config(RectorConfig $rectorConfig): void
{
    // Use ArrayToFirstClassCallableRector in Rector 2.x, FirstClassCallableRector in 1.x
    /** @var class-string<\Rector\Contract\Rector\RectorInterface> $firstClassCallableRector */
    $firstClassCallableRector = class_exists(\Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector::class)
        ? \Rector\Php81\Rector\Array_\ArrayToFirstClassCallableRector::class
        // Referenced as a string because recent Rector 2.x releases removed the class,
        // which makes static analysis fail to resolve it.
        : 'Rector\Php81\Rector\Array_\FirstClassCallableRector';
    $rectorConfig->rule($firstClassCallableRector);

    $rectorConfig->rule(\MLL\RectorConfig\Rector\ElvisToCoalesceRector::class);
    $rectorConfig->rule(\MLL\RectorConfig\Rector\IfThrowToCoalesceThrowRector::class);
}

/**
 * Configure rector with Laravel rules.
 */
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
