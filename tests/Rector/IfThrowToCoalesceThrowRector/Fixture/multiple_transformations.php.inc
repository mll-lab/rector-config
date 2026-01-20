<?php

class MultipleTransformations
{
    public function multiple()
    {
        $first = $this->fetchFirst();
        if ($first === null) {
            throw new \RuntimeException('First is null');
        }

        $second = $this->fetchSecond();
        if (! $second) {
            throw new \RuntimeException('Second is falsy');
        }
    }

    private function fetchFirst(): ?string
    {
        return null;
    }

    private function fetchSecond(): string
    {
        return '';
    }
}

?>
-----
<?php

class MultipleTransformations
{
    public function multiple()
    {
        $first = $this->fetchFirst() ?? throw new \RuntimeException('First is null');

        $second = $this->fetchSecond() ?: throw new \RuntimeException('Second is falsy');
    }

    private function fetchFirst(): ?string
    {
        return null;
    }

    private function fetchSecond(): string
    {
        return '';
    }
}

?>
