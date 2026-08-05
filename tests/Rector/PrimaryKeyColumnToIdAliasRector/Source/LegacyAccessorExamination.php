<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\Source;

use Illuminate\Database\Eloquent\Model;

class LegacyAccessorExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    public function getIdAttribute(): ?string
    {
        return $this->attributes[$this->primaryKey] ?? null;
    }

    public function setIdAttribute(?string $id): void
    {
        $this->attributes[$this->primaryKey] = $id;
    }
}
