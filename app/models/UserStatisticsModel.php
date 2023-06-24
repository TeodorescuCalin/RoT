<?php

require_once('Model.php');

class UserStatisticsModel extends Model {

    public float $learnProgress;
    public float $quizProgress;
    public array $categoryStats;
    public int $learnCountWeekly;
    public int $quizCountWeekly;
    public array $learnWeeklyStats;
    public array $quizWeeklyStats;
    public UserLearnStatistics $learnStatistics;
    public UserQuizStatistics $quizStatistics;
    public array $quizDurations;

    public function __toString(): string {
        return "
        learnProgress={$this->learnProgress},
        quizProgress={$this->quizProgress},
        learnCountWeekly={$this->learnCountWeekly},
        quizCountWeekly={$this->quizCountWeekly},
        statsByCategory=".json_encode($this->categoryStats).
        "learWeeklyStats=".json_encode($this->learnWeeklyStats).
        "quizWeeklyStats=".json_encode($this->quizWeeklyStats).
        "learnStatistics=".json_encode($this->learnStatistics).
        "quizStatistics=".json_encode($this->quizStatistics).
        "quizDurations=".json_encode($this->quizDurations);
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
            'quizWeeklyStats' => $this->quizWeeklyStats,
            'learnStatistics' => $this->learnStatistics,
            'quizStatistics' => $this->quizStatistics,
            'quizDurations' => $this->quizDurations
        ];
    }
}

class UserLearnStatistics implements Stringable, JsonSerializable {
    public int $total;
    public int $failed;
    public int $passed;

    public function __toString(): string
    {
        return "total={$this->total}".
            "failed={$this->failed}".
            "passed={$this->passed}";
    }

    public function jsonSerialize(): array
    {
        return [
            "total" => $this->total,
            "failed" => $this->failed,
            "passed" => $this->passed
        ];
    }
}

class UserQuizStatistics implements Stringable, JsonSerializable {
    public int $total;
    public int $failed;
    public int $passed;
    public int $perfect;
    public int $average_duration;
    public int $shortest_duration;
    public int $longest_duration;


    public function __toString(): string
    {
        return "total={$this->total}".
            "failed={$this->failed}".
            "passed={$this->passed}";
    }

    public function jsonSerialize(): array
    {
        return [
            "total" => $this->total,
            "failed" => $this->failed,
            "passed" => $this->passed,
            "perfect" => $this->perfect,
            "average_duration" => $this->average_duration,
            "shortest_duration" => $this->shortest_duration,
            "longest_duration" => $this->longest_duration
        ];
    }
}