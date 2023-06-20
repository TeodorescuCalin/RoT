<?php


require_once('Model.php');

class LearnQuestionModel extends Model implements Stringable, JsonSerializable {

    public string $text;
    public string | null $image_path;
    public string $type;
    public array $answers;

    public function __toString(): string
    {
        return"
        id={$this->id},
        text={$this->text},
        image_path={$this->image_path},
        type={$this->type},
        answers=".json_encode($this->answers);
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'image_path' => $this->image_path,
            'type' => $this->type,
            'answers' => $this->answers
        ];
    }
}