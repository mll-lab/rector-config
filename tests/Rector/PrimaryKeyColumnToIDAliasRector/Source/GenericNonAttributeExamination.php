<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class GenericNonAttributeExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @return Collection<int, string> */
    public function id(): Collection
    {
        return new Collection(['not an attribute']);
    }
}
