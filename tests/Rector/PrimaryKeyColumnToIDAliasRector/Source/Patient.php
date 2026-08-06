<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /** @var string */
    protected $primaryKey = 'pat_no';
}
