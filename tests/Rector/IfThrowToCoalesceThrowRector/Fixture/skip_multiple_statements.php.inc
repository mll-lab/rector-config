<?php

class SkipMultipleStatements
{
    public function multipleStatements()
    {
        $result = $this->fetchNullable();
        if ($result === null) {
            $this->log('null');
            throw new \RuntimeException('Value is null');
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }

    private function log(string $message): void {}
}

?>
