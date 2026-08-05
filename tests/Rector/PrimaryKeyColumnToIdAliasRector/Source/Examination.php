<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return Attribute<string|null, string|null> */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->attributes[$this->primaryKey] ?? null,
            set: fn (?string $id): array => [$this->primaryKey => $id],
        );
    }
}
