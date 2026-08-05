<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Examination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\LegacyAccessorExamination;

class RewritesPrimaryKeyColumn
{
    public function read(Examination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function write(Examination $examination): void
    {
        $examination->exam_no = 'E-1';
    }

    public function nullsafe(?Examination $examination): ?string
    {
        return $examination?->exam_no;
    }

    public function legacyAccessor(LegacyAccessorExamination $examination): ?string
    {
        return $examination->exam_no;
    }
}

?>
-----
<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Examination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\LegacyAccessorExamination;

class RewritesPrimaryKeyColumn
{
    public function read(Examination $examination): ?string
    {
        return $examination->id;
    }

    public function write(Examination $examination): void
    {
        $examination->id = 'E-1';
    }

    public function nullsafe(?Examination $examination): ?string
    {
        return $examination?->id;
    }

    public function legacyAccessor(LegacyAccessorExamination $examination): ?string
    {
        return $examination->id;
    }
}

?>
