<?php

class SkipIfWithElseif
{
    public function withElseif()
    {
        $result = $this->fetchNullable();
        if ($result === null) {
            throw new \RuntimeException('Value is null');
        } elseif ($result === '') {
            throw new \RuntimeException('Value is empty');
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
