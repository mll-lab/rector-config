<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Expr\BinaryOp\BooleanAnd;
use PhpParser\Node\Expr\BinaryOp\BooleanOr;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\LogicalAnd;
use PhpParser\Node\Expr\BinaryOp\LogicalOr;
use PhpParser\Node\Expr\BinaryOp\LogicalXor;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BooleanNot;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\Ternary;
use PhpParser\Node\Scalar\Int_;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\ElseIf_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\While_;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\PregMatchTruthyCheckRector\PregMatchTruthyCheckRectorTest
 */
final class PregMatchTruthyCheckRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Unify preg_match result checks to the truthy form, so PHPStan narrows $matches', [
            new CodeSample(
                <<<'CODE_SAMPLE'
if (preg_match('/a/', $subject, $matches) !== 1) {
    return;
}
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
if (! preg_match('/a/', $subject, $matches)) {
    return;
}
CODE_SAMPLE,
            ),
        ]);
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [
            Identical::class,
            NotIdentical::class,
            If_::class,
            ElseIf_::class,
            While_::class,
            Do_::class,
            Ternary::class,
            BooleanNot::class,
            BooleanAnd::class,
            BooleanOr::class,
            LogicalAnd::class,
            LogicalOr::class,
            LogicalXor::class,
        ];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof Identical || $node instanceof NotIdentical) {
            return $this->rewriteComparison($node, false);
        }

        return $this->rewriteConditions($node);
    }

    private function rewriteConditions(Node $node): ?Node
    {
        // The shorthand ternary yields its condition, so a bare call would change its value
        if ($node instanceof Ternary && $node->if === null) {
            return null;
        }

        $changed = false;
        foreach ($this->conditionNames($node) as $name) {
            $condition = $node->{$name};
            if (! $condition instanceof Identical && ! $condition instanceof NotIdentical) {
                continue;
            }

            $rewritten = $this->rewriteComparison($condition, true);
            if ($rewritten !== null) {
                $node->{$name} = $rewritten;
                $changed = true;
            }
        }

        return $changed
            ? $node
            : null;
    }

    /** @return array<string> */
    private function conditionNames(Node $node): array
    {
        return match (true) {
            $node instanceof If_,
            $node instanceof ElseIf_,
            $node instanceof While_,
            $node instanceof Do_,
            $node instanceof Ternary => ['cond'],
            $node instanceof BooleanNot => ['expr'],
            default => ['left', 'right'],
        };
    }

    /** A bare call yields int, so it may only replace a comparison whose bool value is merely tested. */
    private function rewriteComparison(Identical|NotIdentical $node, bool $inCondition): ?Node
    {
        $callAndOperand = $this->splitCallAndOperand($node);
        if ($callAndOperand === null) {
            return null;
        }

        [$call, $comparedTo] = $callAndOperand;

        $isNegated = $this->comparisonIsNegated($call, $comparedTo, $node instanceof Identical);
        if ($isNegated === null) {
            return null;
        }

        if ($isNegated) {
            return new BooleanNot($call);
        }

        return $inCondition
            ? $call
            : null;
    }

    /** @return array{FuncCall, Expr}|null */
    private function splitCallAndOperand(Identical|NotIdentical $node): ?array
    {
        if ($this->isPregMatchCall($node->left)) {
            return [$node->left, $node->right];
        }

        if ($this->isPregMatchCall($node->right)) {
            return [$node->right, $node->left];
        }

        return null;
    }

    /** @phpstan-assert-if-true FuncCall $expr */
    private function isPregMatchCall(Expr $expr): bool
    {
        return $expr instanceof FuncCall
            && $this->isNames($expr, ['preg_match', 'Safe\preg_match']);
    }

    /** Whether the comparison equals the negated call, null when the comparison must stay. */
    private function comparisonIsNegated(FuncCall $call, Expr $comparedTo, bool $identical): ?bool
    {
        // Only a literal, so removing the operand cannot drop a side effect
        if (! $comparedTo instanceof Int_ && ! $comparedTo instanceof LNumber) {
            return null;
        }

        if ($comparedTo->value === 1) {
            return ! $identical;
        }

        if ($comparedTo->value === 0 && $this->isName($call, 'Safe\preg_match')) {
            return $identical;
        }

        return null;
    }
}
