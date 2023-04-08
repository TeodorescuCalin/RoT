
let rawFile = new XMLHttpRequest();
rawFile.overrideMimeType("application/json");
rawFile.open("GET", "../../../data/learn/questions.json", true);
rawFile.onreadystatechange = function() {
    if ( rawFile.readyState === 4 && rawFile.status === 200 ) {
        createNewQuestion(rawFile.responseText);
    }
}
rawFile.send(null);

function createNewQuestion(questionFileText){
    const jsonObj = JSON.parse(questionFileText);
    // const questionId = Math.floor(Math.random() * jsonObj.questionList.length);
    const questionId = 2;
    const currentQuestion = jsonObj.questionList[questionId];

    document.getElementById("questionTitle").innerText = currentQuestion.title;
    document.getElementById("questionImage").src = currentQuestion.imagePath;
    document.getElementById("questionId").value = questionId;
    addTags(currentQuestion.tagList);
    switch (currentQuestion.type) {
        case "multipleChoice": {
            generateAnswersArray(
                "multipleChoiceAnswers.json",
                currentQuestion.totalAnswerCount,
                currentQuestion.answers,
                createNewMultipleChoiceDiv
            );
            break;
        }
        case "multipleChoice_long": {
            generateAnswersArray("longMultipleChoiceAnswers.json",
                currentQuestion.totalAnswerCount
                , currentQuestion.answers
                , createNewLongMultipleChoiceDiv
            );
            break;
        }
        case "count": {
            generateAnswersArray("countAnswers.json"
                , currentQuestion.totalAnswerCount
                , currentQuestion.answers
                    .map(e => e.id),
                createNewCountDiv
            );
            break;
        }
    }
}

function newMultipleChoiceAnswer(answer, answerDivIndex) {

    let newAnswerDiv = document.createElement("div");
    newAnswerDiv.id = "answer" + answer.id;
    newAnswerDiv.classList.add("questionContainer__answerBlock__answer");
    newAnswerDiv.classList.add("questionContainer__answerBlock__answer--choice");
    newAnswerDiv.addEventListener('click', () => {
        if ( newAnswerDiv.classList.contains('questionContainer__answerBlock__answer--choice--selected')) {
            newAnswerDiv.classList.remove('questionContainer__answerBlock__answer--choice--selected');
        } else {
            newAnswerDiv.classList.add('questionContainer__answerBlock__answer--choice--selected');
        }
    });

    let span = document.createElement("span");
    span.classList.add("answer__badge");
    span.innerText = String.fromCharCode(65 + answerDivIndex);


    newAnswerDiv.appendChild(span);
    newAnswerDiv.appendChild(document.createTextNode(answer.text));

    return newAnswerDiv;
}

function createNewMultipleChoiceDiv(answers) {
    let leftSideDiv = document.createElement("div");
    let rightSideDiv = document.createElement("div");
    leftSideDiv.id = "questionAnswersLeftSide";
    rightSideDiv.id = "questionAnswersRightSide";
    leftSideDiv.classList.add("questionContainer__answerBlock");
    rightSideDiv.classList.add("questionContainer__answerBlock");

    for(let index = 0; index < answers.length; ++ index ) {
        let answer = newMultipleChoiceAnswer(answers[index], index);

        if ( index < answers.length / 2) {
            leftSideDiv.appendChild(answer);
        } else {
            rightSideDiv.appendChild(answer);
        }
    }

    document.getElementById("questionAnswersBlock").appendChild(leftSideDiv);
    document.getElementById("questionAnswersBlock").appendChild(rightSideDiv);
}

function createNewLongMultipleChoiceDiv(answers) {
    let questionDiv = document.createElement("div");
    questionDiv.classList.add("questionContainer__answerBlock");
    questionDiv.classList.add("questionContainer__answerBlock--long");
    for(let index = 0; index < answers.length; ++ index ) {
        let answer = newMultipleChoiceAnswer(answers[index], index);
        questionDiv.appendChild(answer);
    }

    document.getElementById("questionAnswersBlock").appendChild(questionDiv);
}

function createNewCountDiv(answers) {
    let leftSideDiv = document.createElement("div");
    let rightSideDiv = document.createElement("div");
    leftSideDiv.id = "questionAnswersLeftSide";
    rightSideDiv.id = "questionAnswersRightSide";
    leftSideDiv.classList.add("questionContainer__answerBlock");
    rightSideDiv.classList.add("questionContainer__answerBlock");

    for(let index = 0; index < answers.length; ++ index ) {
        let answer = document.createElement("div");
        answer.id = "answer" + answers[index].id;
        answer.classList.add("questionContainer__answerBlock__answer");
        answer.classList.add("questionContainer__answerBlock__answer--count");

        let label = document.createElement("label");
        label.for = "answer" + index + "Count";
        label.innerText = answers[index].text;

        let input = document.createElement("input");
        input.type = "number";
        input.id = label.for;
        input.min = "0";
        input.max = "9";
        input.value = "0";
        input.onkeydown = () => { return false; };

        if ( index < answers.length / 2) {
            leftSideDiv.appendChild(answer);
        } else {
            rightSideDiv.appendChild(answer);
        }

        answer.appendChild(label);
        answer.appendChild(input);
    }

    document.getElementById("questionAnswersBlock").appendChild(leftSideDiv);
    document.getElementById("questionAnswersBlock").appendChild(rightSideDiv);
}

function generateAnswersArray(
    filename,
    answerCount,
    correctAnswers,
    callback
) {

    let answerFile = new XMLHttpRequest();
    answerFile.overrideMimeType("application/json");
    answerFile.open("GET", "../../../data/learn/" + filename, true);
    answerFile.onreadystatechange = function() {
        if ( answerFile.readyState === 4 && answerFile.status === 200 ) {
            const answerList = JSON.parse(answerFile.responseText).answerList;

            let responseArray = answerList.filter(element => correctAnswers.includes(element.id));

            let groupSize = Math.floor(answerList.length / answerCount);
            let responseGroupArray = responseArray.map(element => Math.floor(element.id/groupSize));

            for(let group = 0; group < answerCount && responseArray.length !== answerCount; ++ group) {
                if(responseGroupArray.includes(group)) {
                    continue;
                }
                if ( Math.random() < 0.5 )
                    responseArray.push(answerList[Math.floor((Math.random() * groupSize)) + group * groupSize]);
                else
                    responseArray.unshift(answerList[Math.floor((Math.random() * groupSize)) + group * groupSize]);
            }

            callback(responseArray);
        }
    }
    answerFile.send(null);
}

function addTags(questionTags) {
    let imageData = document.getElementById("placeInformation");
    for (let index = 0; index < questionTags.length; ++ index) {
        let newMeta = document.createElement("meta");
        newMeta.propety = questionTags[index].property;
        newMeta.content = questionTags[index].value;
        imageData.appendChild(newMeta);
    }
}