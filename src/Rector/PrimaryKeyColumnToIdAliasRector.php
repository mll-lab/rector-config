<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\PrimaryKeyColumnToIdAliasRectorTest
 */
final class PrimaryKeyColumnToIdAliasRector extends AbstractRector
{
    /**
     * Primary key column names of models that alias them through a writable "id" accessor.
     *
     * @var array<class-string, string|null>
     */
    private array $idAliasedPrimaryKeys = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Access model identity through the "id" alias instead of the primary key column name', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$examination->exam_no;
CODE_SAMPLE,
                <<<'CODE_SAMPLE'
$examination->id;
CODE_SAMPLE,
            ),
        ]);
    }

    /** @return array<class-string<Node>> */
    public function getNodeTypes(): array
    {
        return [PropertyFetch::class, NullsafePropertyFetch::class];
    }

    /** @param PropertyFetch|NullsafePropertyFetch $node */
    public function refactor(Node $node): ?Node
    {
        if (! $node->name instanceof Identifier) {
            return null;
        }

        $propertyName = $node->name->toString();
        if ($propertyName === 'id') {
            return null;
        }

        $classNames = $this->getType($node->var)->getObjectClassNames();
        if (count($classNames) !== 1) {
            return null;
        }

        if ($this->idAliasedPrimaryKey($classNames[0]) !== $propertyName) {
            return null;
        }

        $node->name = new Identifier('id');

        return $node;
    }

    private function idAliasedPrimaryKey(string $className): ?string
    {
        if (! array_key_exists($className, $this->idAliasedPrimaryKeys)) {
            $this->idAliasedPrimaryKeys[$className] = self::determineIdAliasedPrimaryKey($className);
        }

        return $this->idAliasedPrimaryKeys[$className];
    }

    private static function determineIdAliasedPrimaryKey(string $className): ?string
    {
        if (! is_a($className, Model::class, true)) {
            return null;
        }

        $reflection = new \ReflectionClass($className);
        if ($reflection->isAbstract()) {
            return null;
        }

        try {
            // The primary key column is only known after construction,
            // models may derive it dynamically, e.g. from a table prefix.
            $model = $reflection->newInstance();
        } catch (\Throwable) {
            // Constructors may require infrastructure that is unavailable during analysis,
            // such as a database connection or service container bindings.
            // Without an instance, the primary key is unknowable, so the model is left alone.
            return null;
        }

        $primaryKey = $model->getKeyName();
        if ($primaryKey === 'id') {
            return null;
        }

        // Rewriting is only safe if the alias can be written as well as read.
        return self::hasWritableIdAccessor($reflection, $model)
            ? $primaryKey
            : null;
    }

    /** @param \ReflectionClass<Model> $reflection */
    private static function hasWritableIdAccessor(\ReflectionClass $reflection, Model $model): bool
    {
        if ($reflection->hasMethod('getIdAttribute')) {
            return $reflection->hasMethod('setIdAttribute');
        }

        if (! $reflection->hasMethod('id')) {
            return false;
        }

        $id = $reflection->getMethod('id');
        if ($id->getNumberOfRequiredParameters() > 0) {
            return false;
        }

        $attribute = $id->invoke($model);

        return $attribute instanceof Attribute
            && $attribute->get !== null
            && $attribute->set !== null;
    }
}
