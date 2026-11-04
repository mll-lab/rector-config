<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use MLL\Utils\StringUtil;
use PhpParser\Node;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Scalar\String_;
use PHPStan\Analyser\Scope;
use Rector\NodeTypeResolver\Node\AttributeKey;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\StringUtilConstantRector\StringUtilConstantRectorTest
 */
final class StringUtilConstantRector extends AbstractRector
{
    /** @var array<string, string>|null */
    private ?array $constantNamesByValue = null;

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Replace string literals equal to a constant of ' . StringUtil::class . ' with the constant', [
            new CodeSample(
                <<<'CODE_SAMPLE'
implode("\r\n", $lines);
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
implode(\MLL\Utils\StringUtil::WINDOWS_NEWLINE, $lines);
CODE_SAMPLE,
            ),
        ]);
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [String_::class];
    }

    /** @param String_ $node */
    public function refactor(Node $node): ?Node
    {
        $constantName = $this->constantNamesByValue()[$node->value] ?? null;
        if ($constantName === null) {
            return null;
        }

        // The constants of StringUtil must keep their literal values.
        $scope = $node->getAttribute(AttributeKey::SCOPE);
        if ($scope instanceof Scope && $scope->getClassReflection()?->getName() === StringUtil::class) {
            return null;
        }

        return new ClassConstFetch(new FullyQualified(StringUtil::class), $constantName);
    }

    /** @return array<string, string> */
    private function constantNamesByValue(): array
    {
        return $this->constantNamesByValue
            ??= self::computeConstantNamesByValue();
    }

    /** @return array<string, string> */
    private static function computeConstantNamesByValue(): array
    {
        if (! class_exists(StringUtil::class)) {
            return [];
        }

        $constantNamesByValue = [];
        foreach ((new \ReflectionClass(StringUtil::class))->getReflectionConstants(\ReflectionClassConstant::IS_PUBLIC) as $constant) {
            $value = $constant->getValue();
            if (is_string($value)) {
                $constantNamesByValue[$value] ??= $constant->getName();
            }
        }

        return $constantNamesByValue;
    }
}
