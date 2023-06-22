<?php

require_once "Model.php";

class UserQuizModel extends Model {

    public int $userId;
    public int $quizId;
    public string | null $status;
    public int | null $duration;
    public int | null $correctAnswerCount;

    public function __toString(): string
    {
        return
        "userId={$this->userId}".
        "quizId={$this->quizId}".
        "status={$this->status}".
        "duration={$this->duration}".
        "correctAnswerCount={$this->correctAnswerCount}";
    }

    public function jsonSerialize(): array
    {
        return [
            'quizId' => $this->quizId,
            'status' => $this->status,
            'duration' => $this->duration,
            'correctAnswerCount' => $this->correctAnswerCount
        ];
    }


}