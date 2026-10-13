<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;

class NotAModel
{
    public ?string $exam_no = null;

    /** @return Attribute<string|null, string|null> */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->exam_no,
            set: fn (?string $id): array => ['exam_no' => $id],
        );
    }
}
