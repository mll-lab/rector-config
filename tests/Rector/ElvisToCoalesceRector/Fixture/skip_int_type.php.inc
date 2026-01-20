<?php

class SkipIntType
{
    public function skip()
    {
        $value = $this->fetchNullableInt() ?: 42;
    }

    private function fetchNullableInt(): ?int
    {
        return 0;
    }
}

?>
