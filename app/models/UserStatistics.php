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
        statsByCategory=".implode($this->categoryStats).
        "learWeeklyStats=".implode($this->learnWeeklyStats).
        "quizWeeklyStats=".implode($this->quizWeeklyStats);
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