<?php

class SkipInterveningStatement
{
    public function intervening()
    {
        $result = $this->fetchNullable();
        $this->log('fetched');
        if ($result === null) {
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
