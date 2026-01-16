<?php

class ExceptionWithArguments
{
    public function withArgs()
    {
        $result = $this->fetchNullable();
        if ($result === null) {
            throw new \InvalidArgumentException('Value not found', 404, new \Exception('Previous'));
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

class ExceptionWithArguments
{
    public function withArgs()
    {
        $result = $this->fetchNullable() ?? throw new \InvalidArgumentException('Value not found', 404, new \Exception('Previous'));
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
