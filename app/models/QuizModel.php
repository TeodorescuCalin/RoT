<?php

require_once "Model.php";

class QuizModel extends Model {

    public array $questions;

    public function __toString(): string
    {
        return "id={$this->id}".
            "questions=".json_encode($this->questions);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'questions' => $this->questions
        ];
    }


}