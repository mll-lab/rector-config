<?php

class SkipNonIdenticalComparison
{
    public function nonIdentical()
    {
        $result = $this->fetchNullable();
        if ($result == null) {
            throw new \RuntimeException('Value is null');
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
