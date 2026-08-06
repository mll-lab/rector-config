<?php

use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Examination;
use MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source\Patient;

class SkipsUnresolvablePropertyNamesAndTypes
{
    public function dynamicPropertyName(Examination $examination, string $propertyName): mixed
    {
        return $examination->{$propertyName};
    }

    public function unionOfModels(Examination|Patient $model): ?string
    {
        return $model->exam_no;
    }

    public function unknownType($model): ?string
    {
        return $model->exam_no;
    }
}

?>
