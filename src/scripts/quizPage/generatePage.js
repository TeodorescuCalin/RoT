let answerDivs = document.getElementsByClassName("questionContainer__answerBlock__answer");
let questionQueue = JSON.parse(sessionStorage.getItem("questionQueue"));
function completeQuestionDiv() {
    for (let index = 0; index < answerDivs.length; ++ index) {
        answerDivs[index].appendChild(document.createTextNode(""));
        answerDivs[index].addEventListener('click', () => {
            if ( answerDivs[index].classList.contains('questionContainer__answerBlock__answer--selected')) {
                answerDivs[index].classList.remove('questionContainer__answerBlock__answer--selected');
            } else {
                answerDivs[index].classList.add('questionContainer__answerBlock__answer--selected');
            }
        })
    }

    document.getElementById("remainingQuestions").appendChild(document.createTextNode(questionQueue.length));
    document.getElementById("correctQuestions").appendChild(document.createTextNode(0));
    document.getElementById("wrongQuestions").appendChild(document.createTextNode(0));
    displayNewQuestion();

}
function displayNewQuestion() {
    clearOldQuestion();
    const currentQuestion = questionQueue[0];
    document.getElementById("questionTitle").innerText = currentQuestion.title;
    const currentQuestionImage = document.getElementById("questionImage");
    if ( currentQuestion.img !== undefined ) {
        if ( currentQuestionImage !== null ) {
            currentQuestionImage.src = currentQuestionImage.img;
        } else {
            const questionImage = document.createElement("img");
            questionImage.src = currentQuestion.img;
            questionImage.id = "questionImage";
            document.getElementById("questionDescription")
                .appendChild(questionImage);
        }
    } else {
        if ( currentQuestionImage !== null ) {
            currentQuestionImage.remove();
        }
    }
    let questionDiv = document.getElementsByClassName("questionContainer__answerBlock__answer");
    for(let index = 0; index < currentQuestion.answers.length; ++ index) {
        questionDiv[index].childNodes[2].textContent = currentQuestion.answers[index].text;
    }
}

function clearOldQuestion() {
    for (let index = 0; index < answerDivs.length; ++ index) {
        answerDivs[index].children[0].style.backgroundColor = "#24252A";
        if (answerDivs[index].classList.contains("questionContainer__answerBlock__answer--selected"))
            answerDivs[index].classList.remove("questionContainer__answerBlock__answer--selected");
    }
}
