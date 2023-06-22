<?php

class QuizQuestionModel extends Model {

    public int $firstAnswerId;
    public int $secondAnswerId;
    public int $thirdAnswerId;
    public bool $firstAnswerCorrect;
    public bool $secondAnswerCorrect;
    public bool $thirdAnswerCorrect;

}