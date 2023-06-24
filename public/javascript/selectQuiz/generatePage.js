
loadPage();

async function loadPage () {
    await fetch ( HOST_URL + "user_quiz" )
        .then( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location = "/public/error"
                }

                const data = response['data'];
                let questionnaireDiv = document.getElementById("questionnaireSelectionTab");

                for ( let index = 0; index < data.length; ++ index ) {
                    let newQuestionnaire = document.createElement("div");
                    newQuestionnaire.classList.add("flexBlock__container");
                    newQuestionnaire.classList.add("quizBlock");
                    newQuestionnaire.id = "questionnaire" + data[index].quizId;

                    let questionnaireTitle = document.createElement("div");
                    questionnaireTitle.classList.add("quizBlock__title");
                    questionnaireTitle.textContent = "Chestionarul  " + (index + 1);

                    let questionnaireStatus = document.createElement("div");
                    questionnaireStatus.classList.add("quizBlock__status");

                    let questionnaireStatistic = null;

                    if ( ! data[index].status) {
                        questionnaireStatus.textContent = "Neîncercat";
                    } else {

                        questionnaireStatistic = createQuestionnaireStatistic(data[index].correctAnswerCount);
                        switch (data[index].status) {
                            case "failed": {
                                questionnaireStatus.textContent = "Esuat";
                                questionnaireStatus.classList.add("quizBlock__status--failed");
                                break;
                            }
                            case "passed": {
                                questionnaireStatus.textContent = "Promovat";
                                questionnaireStatus.classList.add("quizBlock__status--successful");
                                break;
                            }
                            case "perfect": {
                                questionnaireStatus.textContent = "Perfect";
                                questionnaireStatus.classList.add("quizBlock__status--successful");
                                break;
                            }
                        }
                    }

                    let questionnaireStart = document.createElement("button");
                    questionnaireStart.classList.add("quizBlock__start");
                    questionnaireStart.textContent = "Start";
                    questionnaireStart.onclick = function() { displayQuestionnaire(data[index].quizId); }

                    newQuestionnaire.appendChild(questionnaireTitle);
                    newQuestionnaire.appendChild(questionnaireStatus);
                    if ( questionnaireStatistic ) {
                        newQuestionnaire.appendChild(questionnaireStatistic);
                    }
                    newQuestionnaire.appendChild(questionnaireStart);

                    questionnaireDiv.appendChild(newQuestionnaire);
                }
            }
        )
}

function createQuestionnaireStatistic(correctAnswers) {
    let questionnaireStatistic = document.createElement("div");
    questionnaireStatistic.textContent = "Scor:";

    let questionnaireSuccessCount = document.createElement("span");
    questionnaireSuccessCount.classList.add("quizBlock__statistic__tryCount");
    questionnaireSuccessCount.classList.add("quizBlock__statistic__tryCount--success");
    questionnaireSuccessCount.textContent = correctAnswers;

    let questionnaireTryCount = document.createElement("span");
    questionnaireTryCount.classList.add("quizBlock__statistic__tryCount");
    questionnaireTryCount.textContent = "26";

    questionnaireStatistic.appendChild(questionnaireSuccessCount);
    questionnaireStatistic.appendChild(document.createTextNode("/"));
    questionnaireStatistic.appendChild(questionnaireTryCount);

    return questionnaireStatistic;
}

function displayQuestionnaire(quizId) {
    window.location.href = HOST_URL+"quiz/"+quizId;
}