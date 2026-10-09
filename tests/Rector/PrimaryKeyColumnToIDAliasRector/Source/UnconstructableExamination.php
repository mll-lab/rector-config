<?php declare(strict_types=1);

namespace MLL\RectorConfig\Tests\Rector\PrimaryKeyColumnToIDAliasRector\Source;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class UnconstructableExamination extends Model
{
    /** @var string */
    protected $primaryKey = 'exam_no';

    /** @param array<string, mixed> $attributes */
    public function __construct(array $attributes = [])
    {
        throw new \Exception('Requires infrastructure that is unavailable during analysis.');
    }

    /** @return Attribute<string|null, string|null> */
    protected function id(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => $this->attributes[$this->primaryKey] ?? null,
            set: fn (?string $id): array => [$this->primaryKey => $id],
        );
    }
}
