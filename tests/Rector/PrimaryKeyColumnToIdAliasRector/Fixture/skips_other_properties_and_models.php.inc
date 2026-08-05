<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\Source\Examination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\Source\Patient;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIdAliasRector\Source\ReadOnlyIdExamination;

class SkipsOtherPropertiesAndModels
{
    public function otherProperty(Examination $examination): ?string
    {
        return $examination->status;
    }

    public function withoutIdAccessor(Patient $patient): ?string
    {
        return $patient->pat_no;
    }

    public function readOnlyIdAccessor(ReadOnlyIdExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function columnStringInQuery(): void
    {
        Examination::query()->where('exam_no', 'E-1');
    }
}

?>
