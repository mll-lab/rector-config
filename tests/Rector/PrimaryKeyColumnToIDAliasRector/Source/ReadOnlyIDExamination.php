<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ReadOnlyIDExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return Attribute<string|null, never> */
    protected function id(): Attribute
    {
        return Attribute::get(fn (): ?string => $this->attributes[$this->primaryKey] ?? null);
    }
}
