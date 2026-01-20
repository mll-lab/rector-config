<?php

class SkipStringType
{
    public function skip()
    {
        $value = $this->fetchNullableString() ?: 'default';
    }

    private function fetchNullableString(): ?string
    {
        return '';
    }
}

?>
