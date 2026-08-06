<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Examination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Patient;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\ReadOnlyIDExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\ReadOnlyLegacyAccessorExamination;

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

    public function readOnlyIdAccessor(ReadOnlyIDExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function readOnlyLegacyAccessor(ReadOnlyLegacyAccessorExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function columnStringInQuery(): void
    {
        Examination::query()->where('exam_no', 'E-1');
    }
}

?>
