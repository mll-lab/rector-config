<?php

class SkipNonNullable
{
    public function skip()
    {
        $object = $this->fetchObject() ?: new \stdClass();
    }

    private function fetchObject(): \stdClass
    {
        return new \stdClass();
    }
}

?>
