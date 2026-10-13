<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UntypedIDExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** Without type parameters, writability cannot be told apart from read-only access. */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->attributes[$this->primaryKey] ?? null,
            set: fn (?string $id): array => [$this->primaryKey => $id],
        );
    }
}
