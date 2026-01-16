<?php

class NullCoalesce
{
    public function basic()
    {
        $result = $this->fetchNullable();
        if ($result === null) {
            throw new \RuntimeException('Value is null');
        }
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
-----
<?php

class NullCoalesce
{
    public function basic()
    {
        $result = $this->fetchNullable() ?? throw new \RuntimeException('Value is null');
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
