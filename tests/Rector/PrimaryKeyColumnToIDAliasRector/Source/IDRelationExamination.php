<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IDRelationExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return BelongsTo<Patient, $this> */
    public function id(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
