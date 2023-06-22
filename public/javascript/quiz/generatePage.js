let answerDivs = document.getElementsByClassName("questionContainer__answerBlock__answer");
let questionQueue;

getQuestions();
async function getQuestions() {
    const quizId = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    await fetch ( HOST_URL + "quiz/" + quizId + "/questions" )
        .then(response => response.json() )
        .then(
            response => {
                if ( ! response.ok ) {
                    alert(response.error);
                    return;
                }

                questionQueue = response['data'];
                completeQuestionDiv();
            }
        )
}

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
    document.getElementById("questionId").value = currentQuestion.id;
    document.getElementById("questionTitle").innerText = currentQuestion.text;
    const currentQuestionImage = document.getElementById("questionImage");
    if ( currentQuestion.image_path ) {
        if ( currentQuestionImage !== null ) {
            currentQuestionImage.src = currentQuestionImage.image_path;
        } else {
            const questionImage = document.createElement("img");
            questionImage.src = currentQuestion.image_path;
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

    questionDiv[0].childNodes[2].textContent = currentQuestion.first_answer_text;
    questionDiv[0].childNodes[2].id = "answer" + currentQuestion.first_answer_id;
    questionDiv[1].childNodes[2].textContent = currentQuestion.second_answer_text;
    questionDiv[1].childNodes[2].id = "answer" + currentQuestion.second_answer_id;
    questionDiv[2].childNodes[2].textContent = currentQuestion.third_answer_text;
    questionDiv[2].childNodes[2].id = "answer" + currentQuestion.third_answer_id;
}

function clearOldQuestion() {
    for (let index = 0; index < answerDivs.length; ++ index) {
        answerDivs[index].children[0].style.backgroundColor = "#24252A";
        if (answerDivs[index].classList.contains("questionContainer__answerBlock__answer--selected"))
            answerDivs[index].classList.remove("questionContainer__answerBlock__answer--selected");
    }
}
