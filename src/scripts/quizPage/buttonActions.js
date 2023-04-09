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
            break;
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
    if(questionQueue.length === 0){
        displayFinalResult();
    } else {
        displayNewQuestion();
    }
}

function displayFinalResult() {
    document.body.children[1].remove();
    const newDiv = document.createElement("div");
    newDiv.classList.add("finalAnswerDiv");
    newDiv.innerHTML = `Felicitari ai facut <span style="color:green">` + correctQuestions + ` </span> intrebari corecte
        si <span style="color:red">` + wrongQuestions + `</span> intrebari gresite.`
    document.body.appendChild(newDiv);
}