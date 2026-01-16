<?php

class MethodChain
{
    public function chain()
    {
        $result = $this->getService()->fetchData();
        if ($result === null) {
            throw new \RuntimeException('Data not found');
        }
    }

    private function getService(): self
    {
        return $this;
    }

    private function fetchData(): ?string
    {
        return null;
    }
}

?>
-----
<?php

class MethodChain
{
    public function chain()
    {
        $result = $this->getService()->fetchData() ?? throw new \RuntimeException('Data not found');
    }

    private function getService(): self
    {
        return $this;
    }

    private function fetchData(): ?string
    {
        return null;
    }
}

?>
