<?php

require_once "Model.php";

class TipModel extends Model {

    public string $text;

    public function __toString(): string
    {
        return "text={$this->text}";
    }

    public function jsonSerialize(): array
    {
        return [
            "text" => $this->text
        ];
    }


}