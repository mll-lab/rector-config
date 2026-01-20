<?php

class ElvisOperator
{
    public function elvis()
    {
        $result = $this->fetchMaybeFalsy();
        if (! $result) {
            throw new \RuntimeException('Value is falsy');
        }
    }

    private function fetchMaybeFalsy(): string
    {
        return '';
    }
}

?>
-----
<?php

class ElvisOperator
{
    public function elvis()
    {
        $result = $this->fetchMaybeFalsy() ?: throw new \RuntimeException('Value is falsy');
    }

    private function fetchMaybeFalsy(): string
    {
        return '';
    }
}

?>
