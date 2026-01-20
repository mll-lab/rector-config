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
        $type = $this->nodeTypeResolver->getType($expr);

        if (! TypeCombinator::containsNull($type)) {
            return false;
        }

        if (! $type instanceof UnionType) {
            return false;
        }

        foreach ($type->getTypes() as $subType) {
            if ($subType->isArray()->yes()) {
                return false;
            }
        }

        $typeWithoutNull = TypeCombinator::removeNull($type);

        return ! $typeWithoutNull->isScalar()->yes();
    }
}
