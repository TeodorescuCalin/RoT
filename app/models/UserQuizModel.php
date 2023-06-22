<?php

class UserQuizModel extends Model {

    public int $userId;
    public int $quizId;
    public string $status;
    public int $duration;
    public int $correctAnswerCount;

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
            'userId' => $this->userId,
            'quizId' => $this->quizId,
            'status' => $this->status,
            'duration' => $this->duration,
            'correctAnswerCount' => $this->correctAnswerCount
        ];
    }


}