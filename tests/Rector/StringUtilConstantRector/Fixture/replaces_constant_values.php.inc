<?php

namespace MLL\RectorConfig\Tests\Rector\StringUtilConstantRector\Fixture;

final class ReplacesConstantValues
{
    public const SEPARATOR = "\r\n";

    public function run(array $lines): string
    {
        return "\xEF\xBB\xBF" . implode("\r\n", $lines);
    }
}

?>
-----
<?php

namespace MLL\RectorConfig\Tests\Rector\StringUtilConstantRector\Fixture;

final class ReplacesConstantValues
{
    public const SEPARATOR = \MLL\Utils\StringUtil::WINDOWS_NEWLINE;

    public function run(array $lines): string
    {
        return \MLL\Utils\StringUtil::UTF_8_BOM . implode(\MLL\Utils\StringUtil::WINDOWS_NEWLINE, $lines);
    }
}

?>
