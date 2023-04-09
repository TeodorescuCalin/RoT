let questionnaireFile = new XMLHttpRequest();
questionnaireFile.overrideMimeType("application/json");
questionnaireFile.open("GET", "../../../data/quiz/questionnaires.json", true);
questionnaireFile.onreadystatechange = function() {
    if ( questionnaireFile.readyState === 4 && questionnaireFile.status === 200 ) {
        createPage(JSON.parse(questionnaireFile.responseText).questionnaireList);
    }
}
questionnaireFile.send(null);

function createPage(questionnaireList) {

    let questionnaireDiv = document.getElementById("questionnaireSelectionTab");
    for ( let index = 0; index < questionnaireList.length; ++ index ) {
        let newQuestionnaire = document.createElement("div");
        newQuestionnaire.classList.add("flexBlock__container");
        newQuestionnaire.classList.add("quizBlock");
        newQuestionnaire.id = "questionnaire" + index;

        let questionnaireTitle = document.createElement("div");
        questionnaireTitle.classList.add("quizBlock__title");
        questionnaireTitle.textContent = "Chestionarul  " + (index + 1);

        let questionnaireStatus = document.createElement("div");
        questionnaireStatus.classList.add("quizBlock__status");
        questionnaireStatus.textContent = "Neîncercat";

        let questionnaireStatistic = createQuestionnaireStatistic();

        let questionnaireStart = document.createElement("button");
        questionnaireStart.classList.add("quizBlock__start");
        questionnaireStart.textContent = "Start";
        questionnaireStart.onclick = function() { displayQuestionnaire(questionnaireList[index].questionList); }

        newQuestionnaire.appendChild(questionnaireTitle);
        newQuestionnaire.appendChild(questionnaireStatus);
        newQuestionnaire.appendChild(questionnaireStatistic);
        newQuestionnaire.appendChild(questionnaireStart);

        questionnaireDiv.appendChild(newQuestionnaire);
    }
}

function createQuestionnaireStatistic() {
    let questionnaireStatistic = document.createElement("div");
    questionnaireStatistic.textContent = "Scor:";

    let questionnaireSuccessCount = document.createElement("span");
    questionnaireSuccessCount.classList.add("quizBlock__statistic__tryCount");
    questionnaireSuccessCount.classList.add("quizBlock__statistic__tryCount--success");
    questionnaireSuccessCount.textContent = "0";

    let questionnaireTryCount = document.createElement("span");
    questionnaireTryCount.classList.add("quizBlock__statistic__tryCount");
    questionnaireTryCount.textContent = "0";

    questionnaireStatistic.appendChild(questionnaireSuccessCount);
    questionnaireStatistic.appendChild(document.createTextNode("/"));
    questionnaireStatistic.appendChild(questionnaireTryCount);

    return questionnaireStatistic;
}

function displayQuestionnaire(questionList) {
    sessionStorage.setItem("questionQueue", JSON.stringify(questionList));
    window.location.href = "quizPages/quiz.html";
}