<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Model;

class ReadOnlyLegacyAccessorExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    public function getIdAttribute(): ?string
    {
        return $this->attributes[$this->primaryKey] ?? null;
    }
}
