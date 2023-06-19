function nextQuestion() {
    location.reload();
}

function checkAnswers() {
    document.getElementById("sendAnswerButton").disabled = "disabled";

    let questionRequest = new XMLHttpRequest();
    questionRequest.overrideMimeType("application/json");
    questionRequest.open("GET", "../../../data/learn/questions.json", true);
    questionRequest.onreadystatechange = function() {
        if ( questionRequest.readyState === 4 && questionRequest.status === 200 ) {
            const currentQuestion = JSON.parse(questionRequest.responseText).questionList[parseInt(document.getElementById("questionId").value)];
            compareAnswers(currentQuestion);
        }
    }
    questionRequest.send(null);
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