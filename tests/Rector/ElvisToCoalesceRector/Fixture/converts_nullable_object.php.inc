<?php

class ConvertsNullableObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?: throw new \RuntimeException();
    }

    private function fetchNullableObject(): ?\stdClass
    {
        return null;
    }
}

?>
-----
<?php

class ConvertsNullableObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?? throw new \RuntimeException();
    }

    private function fetchNullableObject(): ?\stdClass
    {
        return null;
    }
}

?>
