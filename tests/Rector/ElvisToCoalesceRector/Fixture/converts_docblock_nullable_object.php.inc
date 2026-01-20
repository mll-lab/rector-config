<?php

class ConvertsDocblockNullableObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?: throw new \RuntimeException();
    }

    /** @return \stdClass|null */
    private function fetchNullableObject()
    {
        return null;
    }
}

?>
-----
<?php

class ConvertsDocblockNullableObject
{
    public function convert()
    {
        $object = $this->fetchNullableObject() ?? throw new \RuntimeException();
    }

    /** @return \stdClass|null */
    private function fetchNullableObject()
    {
        return null;
    }
}

?>
