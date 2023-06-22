<?php

require_once "Model.php";

class UserLearnQuestionModel extends Model {

    public string $status;
    public int $userId;
    public int $questionId;

    public function __toString() : string
    {
        return "status={$this->status}".
        "userId={$this->userId}".
        "questionId={$this->questionId}";
    }

    public function jsonSerialize() : array
    {
        return [
            'status' => $this->status
        ];
    }
}