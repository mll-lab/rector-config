<?php

class YodaStyle
{
    public function yoda()
    {
        $result = $this->fetchNullable();
        if (null === $result) {
            throw new \InvalidArgumentException('Yoda says: null it is');
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

class YodaStyle
{
    public function yoda()
    {
        $result = $this->fetchNullable() ?? throw new \InvalidArgumentException('Yoda says: null it is');
    }

    private function fetchNullable(): ?string
    {
        return null;
    }
}

?>
