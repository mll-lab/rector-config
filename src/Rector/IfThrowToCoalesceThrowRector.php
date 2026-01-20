<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Expr\Throw_ as ExprThrow;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Throw_ as StmtThrow;
use Rector\PhpParser\Node\Value\ValueResolver;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\IfThrowToCoalesceThrowRector\IfThrowToCoalesceThrowRectorTest
 */
final class IfThrowToCoalesceThrowRector extends AbstractRector implements MinPhpVersionInterface
{
    use NullFalsyTypeTrait;

    public function __construct(
        private readonly ValueResolver $valueResolver,
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Transform if-null/falsy-throw patterns to coalesce throw expressions', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$result = $this->fetchNullable();
if ($result === null) {
    throw new NullException;
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$result = $this->fetchNullable()
    ?? throw new NullException;
CODE_SAMPLE,
            ),
            new CodeSample(
                <<<'CODE_SAMPLE'
$result = $this->fetchMaybeFalsy();
if (! $result) {
    throw new FalsyException;
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$result = $this->fetchMaybeFalsy()
    ?: throw new FalsyException;
CODE_SAMPLE,
            ),
        ]);
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class, Function_::class];
    }

    /** @param ClassMethod|Function_ $node */
    public function refactor(Node $node): ?Node
    {
        if ($node->stmts === null) {
            return null;
        }

        $hasChanged = false;

        foreach ($node->stmts as $key => $stmt) {
            if (! $stmt instanceof Expression) {
                continue;
            }

            $expr = $stmt->expr;
            if (! $expr instanceof Assign) {
                continue;
            }

            if (! $expr->var instanceof Variable) {
                continue;
            }

            $nextStmt = $node->stmts[$key + 1] ?? null;
            if (! $nextStmt instanceof If_) {
                continue;
            }

            if ($nextStmt->else !== null || $nextStmt->elseifs !== []) {
                continue;
            }

            if (count($nextStmt->stmts) !== 1) {
                continue;
            }

            $ifStmt = $nextStmt->stmts[0];

            $throwExpr = null;
            if ($ifStmt instanceof StmtThrow) {
                $throwExpr = new ExprThrow($ifStmt->expr);
            } elseif ($ifStmt instanceof Expression
                && $ifStmt->expr instanceof ExprThrow
            ) {
                $throwExpr = $ifStmt->expr;
            }

            if ($throwExpr === null) {
                continue;
            }

            $result = $this->matchCoalescePattern($expr, $nextStmt, $throwExpr);
            if ($result === null) {
                continue;
            }

            $expr->expr = $result;
            unset($node->stmts[$key + 1]);
            $hasChanged = true;
        }

        return $hasChanged
            ? $node
            : null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_80;
    }

    private function matchCoalescePattern(Assign $assign, If_ $if, ExprThrow $throw): ?Expr
    {
        $variable = $assign->var;
        $condition = $if->cond;

        if (($condition instanceof Identical || $condition instanceof Equal)
            && $this->isNullComparison($condition, $variable)
        ) {
            return new Coalesce($assign->expr, $throw);
        }

        if ($condition instanceof BooleanNot
            && $this->nodeComparator->areNodesEqual($condition->expr, $variable)
        ) {
            if ($this->isOnlyNullFalsy($assign->expr)) {
                return new Coalesce($assign->expr, $throw);
            }

            return new Ternary($assign->expr, null, $throw);
        }

        return null;
    }

    private function isNullComparison(Identical|Equal $comparison, Variable $variable): bool
    {
        $left = $comparison->left;
        $right = $comparison->right;

        if ($this->nodeComparator->areNodesEqual($left, $variable)
            && $this->valueResolver->isNull($right)
        ) {
            return true;
        }

        return $this->valueResolver->isNull($left)
            && $this->nodeComparator->areNodesEqual($right, $variable);
    }
}
