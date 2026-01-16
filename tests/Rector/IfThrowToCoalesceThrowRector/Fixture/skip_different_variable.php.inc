<?php

class SkipDifferentVariable
{
    public function differentVar()
    {
        $result = $this->fetchNullable();
        if ($other === null) {
            throw new \RuntimeException('Other is null');
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
