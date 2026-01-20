<?php

class ElvisOperatorToCoalesce
{
    public function convert()
    {
        $result = $this->fetchNullableObject();
        if (! $result) {
            throw new \RuntimeException('Value is null');
        }
    }

    private function fetchNullableObject(): ?\stdClass
    {
        return null;
    }
}

?>
-----
<?php

class ElvisOperatorToCoalesce
{
    public function convert()
    {
        $result = $this->fetchNullableObject() ?? throw new \RuntimeException('Value is null');
    }

    private function fetchNullableObject(): ?\stdClass
    {
        return null;
    }
}

?>
