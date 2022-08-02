<?php declare(strict_types=1);

namespace MLL\RectorConfig;

use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector;

/**
 * Configure rector with MLL rules.
 */
function config(RectorConfig $rectorConfig): void
{
    /**
     * Sometimes fails to recognize children, see https://github.com/rectorphp/rector/blob/main/docs/static_reflection_and_autoload.md#troubleshooting,
     * and never recognizes when a class is mocked (which final classes do not allow for).
     *
     * To ignore this rule, add a comment like this to the PHPDoc of the offending class:
     *
     * @noRector \Rector\Privatization\Rector\Class_\FinalizeClassesWithoutChildrenRector <has children|mocked>
     */
    $rectorConfig->rule(FinalizeClassesWithoutChildrenRector::class);
}
