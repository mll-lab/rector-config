<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

/**
 * Models like this derive their primary key from a table prefix during construction,
 * so the column is not visible in the declared default of the property.
 */
class PrefixedExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'no';

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        $this->primaryKey = "exam_{$this->primaryKey}";

        parent::__construct($attributes);
    }

    /** @return Attribute<string|null, string|null> */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->attributes[$this->primaryKey] ?? null,
            set: fn (?string $id): array => [$this->primaryKey => $id],
        );
    }
}
