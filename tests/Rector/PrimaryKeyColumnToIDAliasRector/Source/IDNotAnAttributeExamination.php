<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Model;

class IDNotAnAttributeExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    public function id(): \stdClass
    {
        return new \stdClass();
    }
}
