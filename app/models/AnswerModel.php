<?php

class AnswerModel extends Model {

    public string $text;

    public function __toString(): string
    {
        return "id={$this->id}".
            "text={$this->text}";
    }

    public function jsonSerialize(): array
    {
        return [
            "id" => $this->id,
            "text" => $this->text
        ];
    }


}