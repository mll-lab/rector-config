<?php

namespace MLL\RectorConfig\Tests\Rector\StringUtilConstantRector\Fixture;

final class SkipsOtherStrings
{
    public function run(array $lines, string $line): string
    {
        return implode("\n", $lines) . '\r\n' . "{$line}\r\n" . "\r\n\r\n";
    }
}
