<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use PhpParser\Node\Expr;
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

trait NullFalsyTypeTrait
{
    /** Check if an expression's type can only be falsy via null (e.g., object|null). */
    private function isOnlyNullFalsy(Expr $expr): bool
    {
        $nativeType = $this->nodeTypeResolver->getNativeType($expr);

        if (! TypeCombinator::containsNull($nativeType)) {
            return false;
        }

        if (! $nativeType instanceof UnionType) {
            return false;
        }

        foreach ($nativeType->getTypes() as $subType) {
            if ($subType->isArray()->yes()) {
                return false;
            }
        }

        $typeWithoutNull = TypeCombinator::removeNull($nativeType);

        return ! $typeWithoutNull->isScalar()->yes();
    }
}
