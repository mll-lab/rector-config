<?php declare(strict_types=1);

namespace MLL\RectorConfig;

use PHPStan\Type\ObjectType;
use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Array_\FirstClassCallableRector;
use Rector\Transform\Rector\FuncCall\FuncCallToNewRector;
use Rector\Transform\Rector\MethodCall\MethodCallToNewRector;
use Rector\Transform\ValueObject\MethodCallToNew;

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
        'collect' => 'Illuminate\\Support\\Collection',
    ]);
    $rectorConfig->ruleWithConfiguration(MethodCallToNewRector::class, [
        new MethodCallToNew(
            objectType: new ObjectType('Illuminate\\Support\\Collection'),
            methodName: 'make',
            newClassString: 'Illuminate\\Support\\Collection',
        ),
    ]);
}
