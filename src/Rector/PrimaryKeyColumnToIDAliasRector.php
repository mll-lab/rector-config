<?php declare(strict_types=1);

namespace MLL\RectorConfig\Rector;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node;
use PhpParser\Node\Expr\NullsafePropertyFetch;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Identifier;
use PHPStan\Reflection\ClassReflection;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Type\Generic\GenericObjectType;
use PHPStan\Type\NeverType;
use PHPStan\Type\Type;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\PrimaryKeyColumnToIDAliasRectorTest
 */
final class PrimaryKeyColumnToIDAliasRector extends AbstractRector
{
    /**
     * Primary key column names of models that alias them through a writable "id" accessor.
     *
     * @var array<class-string, string|null>
     */
    private array $idAliasedPrimaryKeys = [];

    public function __construct(
        private ReflectionProvider $reflectionProvider,
    ) {}

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
            // Already the alias, skipped before paying for type inference below.
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
            $this->idAliasedPrimaryKeys[$className] = $this->determineIDAliasedPrimaryKey($className);
        }

        return $this->idAliasedPrimaryKeys[$className];
    }

    private function determineIDAliasedPrimaryKey(string $className): ?string
    {
        $classReflection = $this->reflectionProvider->getClass($className);
        if (! $classReflection->isSubclassOf(Model::class)) {
            return null;
        }

        if ($classReflection->isAbstract()) {
            // Subclasses may declare another primary key,
            // so the column behind the declared type is not pinned down.
            return null;
        }

        // Rewriting is only safe if the alias can be written as well as read.
        if (! self::hasWritableIDAccessor($classReflection)) {
            return null;
        }

        // Models keyed by "id" need no rewriting, the caller already skipped that property name.
        return self::primaryKeyColumn($classReflection);
    }

    /** @param ClassReflection $classReflection Describes a subclass of Model */
    private static function hasWritableIDAccessor(ClassReflection $classReflection): bool
    {
        if ($classReflection->hasNativeMethod('getIdAttribute')) {
            return $classReflection->hasNativeMethod('setIdAttribute');
        }

        if (! $classReflection->hasNativeMethod('id')) {
            return false;
        }

        // Native methods always have exactly one variant, only PHP internals are overloaded.
        $id = $classReflection->getNativeMethod('id')->getVariants()[0];
        foreach ($id->getParameters() as $parameter) {
            if (! $parameter->isOptional()) {
                // Eloquent calls accessors without arguments, so this cannot be one.
                return false;
            }
        }

        return self::isReadableAndWritableAttribute($id->getReturnType());
    }

    private static function isReadableAndWritableAttribute(Type $returnType): bool
    {
        if (! $returnType instanceof GenericObjectType) {
            // Without the type parameters of the "@return Attribute<TGet, TSet>" annotation,
            // read-only accessors are indistinguishable from writable ones.
            return false;
        }

        if ($returnType->getClassName() !== Attribute::class) {
            return false;
        }

        // Attribute<TGet, TSet>, where Attribute::get() leaves TSet
        // and Attribute::set() leaves TGet as never.
        [$get, $set] = $returnType->getTypes() + [null, null];

        return $get instanceof Type
            && ! $get instanceof NeverType
            && $set instanceof Type
            && ! $set instanceof NeverType;
    }

    /**
     * Models may derive their primary key during construction, e.g. from a table prefix,
     * so it is only knowable from an instance. This constructor call is the sole part of
     * the analyzed code that this rule runs, mirroring larastan, which also instantiates
     * models to reflect on their keys and columns.
     */
    private static function primaryKeyColumn(ClassReflection $classReflection): ?string
    {
        try {
            $model = $classReflection->getNativeReflection()->newInstance();
        } catch (\Throwable) {
            // Constructors may require infrastructure that is unavailable during analysis,
            // such as a database connection or service container bindings.
            // Without an instance, the primary key is unknowable, so the model is left alone.
            return null;
        }

        return $model->getKeyName();
    }
}
