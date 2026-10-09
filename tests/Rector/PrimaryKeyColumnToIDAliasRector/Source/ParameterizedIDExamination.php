<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ParameterizedIDExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return Attribute<string|null, string|null> */
    public function id(string $prefix): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $prefix . ($this->attributes[$this->primaryKey] ?? null),
            set: fn (?string $id): array => [$this->primaryKey => $id],
        );
    }
}
