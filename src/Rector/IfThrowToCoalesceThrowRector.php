<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BooleanNot;
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
    public function __construct(
        private readonly ValueResolver $valueResolver,
    ) {}

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Transform if-null-throw patterns to coalesce throw expressions', [
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

            if (! $stmt->expr instanceof Assign) {
                continue;
            }

            $assign = $stmt->expr;
            if (! $assign->var instanceof Variable) {
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

            // Handle both Stmt\Throw_ (traditional) and Expression containing Expr\Throw_ (PHP 8.0+)
            $throwExpr = null;
            if ($ifStmt instanceof StmtThrow) {
                $throwExpr = new ExprThrow($ifStmt->expr);
            } elseif ($ifStmt instanceof Expression && $ifStmt->expr instanceof ExprThrow) {
                $throwExpr = $ifStmt->expr;
            }

            if ($throwExpr === null) {
                continue;
            }

            $result = $this->matchCoalescePattern($assign, $nextStmt, $throwExpr);
            if ($result === null) {
                continue;
            }

            $assign->expr = $result;
            unset($node->stmts[$key + 1]);
            $hasChanged = true;
        }

        return $hasChanged ? $node : null;
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_80;
    }

    /** Match if the pattern is a null coalesce or elvis coalesce. */
    private function matchCoalescePattern(Assign $assign, If_ $if, ExprThrow $throw): ?Expr
    {
        $variable = $assign->var;
        $condition = $if->cond;

        // Pattern 1: if ($var === null) - null coalesce
        if ($condition instanceof Identical && $this->isNullIdentical($condition, $variable)) {
            return new Coalesce($assign->expr, $throw);
        }

        // Pattern 2: if (! $var) - elvis operator
        if ($condition instanceof BooleanNot
            && $this->nodeComparator->areNodesEqual($condition->expr, $variable)) {
            return new Expr\Ternary($assign->expr, null, $throw);
        }

        return null;
    }

    /** Check if condition is `$var === null` or `null === $var` (Yoda style). */
    private function isNullIdentical(Identical $identical, Variable $variable): bool
    {
        // $var === null
        if ($this->nodeComparator->areNodesEqual($identical->left, $variable)
            && $this->valueResolver->isNull($identical->right)) {
            return true;
        }

        // null === $var (Yoda style)
        return $this->valueResolver->isNull($identical->left)
            && $this->nodeComparator->areNodesEqual($identical->right, $variable);
    }
}
