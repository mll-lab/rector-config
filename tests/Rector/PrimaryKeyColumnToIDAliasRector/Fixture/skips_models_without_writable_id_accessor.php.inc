<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\AbstractExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\GenericNonAttributeExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\IDNotAnAttributeExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\IDRelationExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\NotAModel;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\ParameterizedIDExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\UnconstructableExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\UntypedIDExamination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\WriteOnlyIDExamination;

class SkipsModelsWithoutWritableIdAccessor
{
    public function notAModel(NotAModel $notAModel): ?string
    {
        return $notAModel->exam_no;
    }

    public function abstractModel(AbstractExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function unconstructableModel(UnconstructableExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function idIsNotAnAttribute(IDNotAnAttributeExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function idIsAGenericNonAttribute(GenericNonAttributeExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function idIsARelation(IDRelationExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function idRequiresParameter(ParameterizedIDExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function idWithoutTypeParameters(UntypedIDExamination $examination): ?string
    {
        return $examination->exam_no;
    }

    public function writeOnlyIdAccessor(WriteOnlyIDExamination $examination): ?string
    {
        return $examination->exam_no;
    }
}

?>
