<?php

class ConvertsUnionNullObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?: throw new \RuntimeException();
    }

    private function fetchNullableObject(): \stdClass|null
    {
        return null;
    }
}

?>
-----
<?php

class ConvertsUnionNullObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?? throw new \RuntimeException();
    }

    private function fetchNullableObject(): \stdClass|null
    {
        return null;
    }
}

?>
