<?php

class NonIdenticalComparison
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
-----
<?php

class NonIdenticalComparison
{
    public function nonIdentical()
    {
        $result = $this->fetchNullable() ?? throw new \RuntimeException('Value is null');
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
