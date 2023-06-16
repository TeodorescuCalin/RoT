<?php

class UserStatistics implements Stringable, JsonSerializable {

    public float $learnProgress;
    public float $quizProgress;
    public array $categoryStats;
    public int $learnCountWeekly;
    public int $quizCountWeekly;
    public array $learnWeeklyStats;
    public array $quizWeeklyStats;

    public function __toString(): string {
        return "
        learnProgress={$this->learnProgress},
        quizProgress={$this->quizProgress},
        learnCountWeekly={$this->learnCountWeekly},
        quizCountWeekly={$this->quizCountWeekly},
        statsByCategory=".json_encode($this->categoryStats).
        "learWeeklyStats=".json_encode($this->learnWeeklyStats).
        "quizWeeklyStats=".json_encode($this->quizWeeklyStats);
    }

    public function jsonSerialize(): array
    {
        return [
            'learnProgress' => $this->learnProgress,
            'quizProgress' => $this->quizProgress,
            'learnCountWeekly' => $this->learnCountWeekly,
            'quizCountWeekly' => $this->quizCountWeekly,
            'statsByCategory' => $this->categoryStats,
            'learnWeeklyStats' => $this->learnWeeklyStats,
            'quizWeeklyStats' => $this->quizWeeklyStats
        ];
    }


}