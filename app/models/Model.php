<?php

class Model {
    public int $id;

    public function __toString(): string
    {
        return "id={$this->id}";
    }

}