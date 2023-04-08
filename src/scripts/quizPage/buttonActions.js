let correctQuestions = 0;
let wrongQuestions = 0;

function nextQuestion() {
    const currentQuestion = questionQueue[0];
    questionQueue.shift();
    questionQueue.push(currentQuestion);
    displayNewQuestion();
}
function checkAnswers() {
    const currentQuestion = questionQueue[0];
    let questionCorrect = true;
    for (let index = 0; index < currentQuestion.answers.length; ++ index) {
        if (
            (answerDivs[index].classList
                .contains("questionContainer__answerBlock__answer--selected") &&
            ! currentQuestion.answers[index].correct) ||
            (! answerDivs[index].classList
                .contains("questionContainer__answerBlock__answer--selected") &&
            currentQuestion.answers[index].correct)
        ) {
            questionCorrect = false;
        }
    }
    if (questionCorrect) {
        correctQuestions ++;
    } else {
        wrongQuestions ++;
    }
    questionQueue.shift();

    document.getElementById("correctQuestions").innerText = correctQuestions + "";
    document.getElementById("wrongQuestions").innerText = wrongQuestions + "";
    document.getElementById("remainingQuestions").innerText = questionQueue.length + "";
    displayNewQuestion();
}