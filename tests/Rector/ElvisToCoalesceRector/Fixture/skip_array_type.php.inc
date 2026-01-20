<?php

class SkipArrayType
{
    public function skip()
    {
        /** @var array<int>|null $value */
        $value = $this->fetchNullableArray() ?: [1, 2, 3];
    }

    /** @return array<int>|null */
    private function fetchNullableArray(): ?array
    {
        return [];
    }
}

?>
