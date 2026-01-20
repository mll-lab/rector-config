<?php

class SkipIfWithElse
{
    public function withElse()
    {
        $result = $this->fetchNullable();
        if ($result === null) {
            throw new \RuntimeException('Value is null');
        } else {
            echo $result;
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
