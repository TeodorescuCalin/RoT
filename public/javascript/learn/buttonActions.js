async function nextQuestion() {
    if ( document.getElementById("sendAnswerButton").disabled ) {
        location.reload();
    } else {
        await fetch (
            new Request (
                HOST_URL + "learn/questions/" + document.getElementById("questionId").value + "/status",
                {
                    method : "PUT",
                    headers : {
                        "Content-Type" : "application/json"
                    },
                    body : JSON.stringify({
                        "skipped" : true
                    })
                }
            )
        ).then( response => response.json() )
            .then(
                response => {
                    if ( ! response.ok ) {
                        alert ( response.error );
                    }
                    location.reload();
                }
            )
    }
}

async function checkAnswers() {
    document.getElementById("sendAnswerButton").disabled = "disabled";

    const answers = getQuestionAnswers();

    await fetch (
        new Request (
            HOST_URL + "learn/questions/" + document.getElementById("questionId").value + "/status",
            {
                    method : "PUT",
                    headers : {
                        "Content-Type" : "application/json"
                    },
                    body : JSON.stringify({
                        "answers" : answers
                    })
                }
        )
    ).then( response => response.json() )
        .then(
            response => {
                if ( ! response.ok ) {
                    alert ( response.error );
                    return;
                }
                displayResult(response.data);
            }
        )
}

function getQuestionAnswers() {
    let result = [];

    if ( document.getElementById("questionType").value === 'count' ) {
        const divAnswers = document.getElementsByClassName("questionContainer__answerBlock__answer");
        for (let index = 0; index < divAnswers.length; ++ index) {
            const currentAnswerId = parseInt(divAnswers[index].id.slice(6));
            const currentAnswerValue = parseInt(divAnswers[index].children[1].value);
            result.push(
                {
                    "id" : currentAnswerId,
                    "count" : currentAnswerValue
                }
            );
        }
    } else {
        const divAnswers = document.getElementsByClassName("questionContainer__answerBlock__answer");
        for (let index = 0; index < divAnswers.length; ++ index) {
            const currentAnswerId = parseInt(divAnswers[index].id.slice(6));
            if ( divAnswers[index].classList.contains("questionContainer__answerBlock__answer--choice--selected") ) {
                result.push(
                    {
                        "id" : currentAnswerId,
                        "selected" : true
                    }
                );
            } else {
                result.push(
                    {
                        "id" : currentAnswerId,
                        "selected" : false
                    }
                );
            }
        }
    }

    return result;
}

function displayResult(answers) {

    for ( let index = 0; index < answers.length; ++ index ) {
        const answer = answers[index];
        let element = document.getElementById('answer' + answer['id']);
        if (answer['correct']) {
            element.style.backgroundColor = "green";
        } else {
            element.style.backgroundColor = "red";
        }

    }

    document.getElementById("questionExplanation").innerText = document.getElementById('questionExplanationHidden').value;
}

function compareAnswers(currentQuestion) {
    if(currentQuestion.type === "count") {
        const divAnswers = document.getElementsByClassName("questionContainer__answerBlock__answer");
        for (let index = 0; index < divAnswers.length; ++ index) {
            const currentAnswerId = parseInt(divAnswers[index].id.slice(6));
            const currentAnswerValue = parseInt(divAnswers[index].children[1].value);
            const isPartOfAnswer = currentQuestion.answers
                                    .find(e => e.id === currentAnswerId);
            if (isPartOfAnswer === undefined) {
                if (currentAnswerValue !== 0) {
                    divAnswers[index].style.backgroundColor = "red";
                } else {
                    divAnswers[index].style.backgroundColor = "green";
                }
            } else {
                if (currentAnswerValue !== isPartOfAnswer.count) {
                    divAnswers[index].style.backgroundColor = "red";
                } else {
                    divAnswers[index].style.backgroundColor = "green";
                }
            }
        }

    } else {
        const divAnswers = document.getElementsByClassName("questionContainer__answerBlock__answer");
        for (let index = 0; index < divAnswers.length; ++ index) {
            const currentAnswerId = parseInt(divAnswers[index].id.slice(6));
            if(currentQuestion.answers.includes(currentAnswerId)) {
                divAnswers[index].firstChild.style.backgroundColor = "green";
            } else {
                divAnswers[index].firstChild.style.backgroundColor = "red";
            }
        }
    }
    document.getElementById("questionExplanation").innerHTML = currentQuestion.explanation;
}