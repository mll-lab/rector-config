<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class WriteOnlyIDExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return Attribute<never, string|null> */
    protected function id(): Attribute
    {
        return Attribute::set(fn (?string $id): array => [$this->primaryKey => $id]);
    }
}
