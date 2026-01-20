<?php

class SkipRegularTernary
{
    public function skip()
    {
        $object = $this->fetchNullableObject() ? 'yes' : 'no';
    }

    private function fetchNullableObject(): ?\stdClass
    {
        return null;
    }
}

?>
