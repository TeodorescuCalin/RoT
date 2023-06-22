<?php

require_once "Model.php";

class QuizQuestionModel extends Model {

    public string $text;
    public string | null $image_path;
    public int $first_answer_id;
    public int $second_answer_id;
    public int $third_answer_id;
    public string $first_answer_text;
    public string $second_answer_text;
    public string $third_answer_text;
    public bool $first_answer_correct;
    public bool $second_answer_correct;
    public bool $third_answer_correct;

    public function __toString(): string
    {
        return "id={$this->id}".
            "text={$this->text}".
            "image_path={$this->image_path}".
            "first_answer_id={$this->first_answer_id}".
            "second_answer_id={$this->second_answer_id}".
            "third_answer_id={$this->third_answer_id}".
            "first_answer_correct={$this->first_answer_correct}".
            "second_answer_correct={$this->second_answer_correct}".
            "third_answer_correct={$this->third_answer_correct}".
            "first_answer_text={$this->first_answer_text}".
            "second_answer_text={$this->second_answer_text}".
            "third_answer_text={$this->third_answer_text}";
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'image_path' => $this->image_path,
            'first_answer_id' => $this->first_answer_id,
            'second_answer_id' => $this->second_answer_id,
            'third_answer_id' => $this->third_answer_id,
            'first_answer_correct' => $this->first_answer_correct,
            'second_answer_correct' => $this->second_answer_correct,
            'third_answer_correct' => $this->third_answer_correct,
            'first_answer_text' => $this->first_answer_text,
            'second_answer_text' => $this->second_answer_text,
            'third_answer_text' => $this->third_answer_text
        ];
    }


}