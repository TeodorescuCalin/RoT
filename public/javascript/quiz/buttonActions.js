let correctQuestions = 0;
let wrongQuestions = 0;

function nextQuestion() {
    const currentQuestion = questionQueue[0];
    questionQueue.shift();
    questionQueue.push(currentQuestion);
    displayNewQuestion();
}
async function checkAnswers() {
    const quizId = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    const questionDiv = document.getElementsByClassName("questionContainer__answerBlock__answer");
    await fetch (
        new Request(HOST_URL + "quizzes/" + quizId + "/" + document.getElementById("questionId").value + "/check",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : JSON.stringify(
                    {
                        "first_answer_id" : questionDiv[0].childNodes[2].id.substring(6),
                        "first_answer_selected" : answerDivs[0].classList.contains("questionContainer__answerBlock__answer--selected"),
                        "second_answer_id" : questionDiv[1].childNodes[2].id.substring(6),
                        "second_answer_selected" : answerDivs[1].classList.contains("questionContainer__answerBlock__answer--selected"),
                        "third_answer_id" : questionDiv[2].childNodes[2].id.substring(6),
                        "third_answer_selected" : answerDivs[2].classList.contains("questionContainer__answerBlock__answer--selected"),
                    }
                )
            }
        )
    ).then( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location = "/public/error"
                }

                if ( response['data'].solved ) {
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
        )
}

async function displayFinalResult() {
    const quizId = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    document.body.children[1].remove();

    await fetch (
        new Request(
            HOST_URL + "quizzes/" + quizId + "/user_status",
            {
                method : "PUT",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : JSON.stringify(
                    {
                        "correct_answers" : correctQuestions,
                        "duration" : 1800 - remainingTime
                    }
                )
            }
        )
    ).then( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location = "/public/error"
                }

                const newDiv = document.createElement("div");
                newDiv.classList.add("finalAnswerDiv");
                newDiv.innerHTML = response['data'].message;
                document.body.appendChild(newDiv);
            })
}