<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use PhpParser\Node;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\Ternary;
use Rector\Rector\AbstractRector;
use Rector\ValueObject\PhpVersion;
use Rector\VersionBonding\Contract\MinPhpVersionInterface;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\ElvisToCoalesceRector\ElvisToCoalesceRectorTest
 */
final class ElvisToCoalesceRector extends AbstractRector implements MinPhpVersionInterface
{
    use NullFalsyTypeTrait;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert elvis operator (?:) to null coalesce (??) when expression type can only be falsy via null', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$object = $this->fetchNullableObject() ?: throw new \RuntimeException();
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$object = $this->fetchNullableObject() ?? throw new \RuntimeException();
CODE_SAMPLE,
            ),
        ]);
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [Ternary::class];
    }

    /** @param Ternary $node */
    public function refactor(Node $node): ?Node
    {
        if ($node->if !== null) {
            return null;
        }

        if (! $this->isOnlyNullFalsy($node->cond)) {
            return null;
        }

        return new Coalesce($node->cond, $node->else);
    }

    public function provideMinPhpVersion(): int
    {
        return PhpVersion::PHP_70;
    }
}
