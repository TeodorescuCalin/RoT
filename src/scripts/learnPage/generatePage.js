
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
    const questionId = Math.floor(Math.random() * jsonObj.questionList.length);
    const currentQuestion = jsonObj.questionList[questionId];

    document.getElementById("questionTitle").innerText = currentQuestion.title;
    document.getElementById("questionImage").src = currentQuestion.imagePath;
    switch (currentQuestion.type) {
        case "multipleChoice": {
            generateAnswersArray("multipleChoiceAnswers.json", currentQuestion.totalAnswerCount, currentQuestion.answers, createNewMultipleChoiceDiv);
            break;
        }
        case "multipleChoice_long": {
            generateAnswersArray("longMultipleChoiceAnswers.json", currentQuestion.totalAnswerCount, currentQuestion.answers, createNewLongMultipleChoiceDiv);
            break;
        }
        case "count": {
            generateAnswersArray("countAnswers.json", currentQuestion.totalAnswerCount, currentQuestion.answers, createNewCountDiv);
            break;
        }
    }
}

function newMultipleChoiceAnswer(index, text) {

    let answer = document.createElement("div");
    answer.id = "answer" + index;
    answer.classList.add("questionContainer__answerBlock__answer");
    answer.classList.add("questionContainer__answerBlock__answer--choice");
    answer.addEventListener('click', () => {
        if ( answer.classList.contains('questionContainer__answerBlock__answer--choice--selected')) {
            answer.classList.remove('questionContainer__answerBlock__answer--choice--selected');
        } else {
            answer.classList.add('questionContainer__answerBlock__answer--choice--selected');
        }
    })

    let span = document.createElement("span");
    span.classList.add("answer__badge");
    span.innerText = String.fromCharCode(65 + index);


    answer.appendChild(span);
    answer.appendChild(document.createTextNode(text));

    return answer;
}

function createNewMultipleChoiceDiv(answers) {
    let leftSideDiv = document.createElement("div");
    let rightSideDiv = document.createElement("div");
    leftSideDiv.id = "questionAnswersLeftSide";
    rightSideDiv.id = "questionAnswersRightSide";
    leftSideDiv.classList.add("questionContainer__answerBlock");
    rightSideDiv.classList.add("questionContainer__answerBlock");

    for(let index = 0; index < answers.length; ++ index ) {
        let answer = newMultipleChoiceAnswer(index, answers[index].text);

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
        let answer = newMultipleChoiceAnswer(index, answers[index].text);
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
        answer.id = "answer" + index;
        answer.classList.add("questionContainer__answerBlock__answer");
        answer.classList.add("questionContainer__answerBlock__answer--count");

        let label = document.createElement("label");
        label.for = "answer" + index + "Count";

        let image = document.createElement("img");
        image.src = answers[index].source;

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
        label.appendChild(image);
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

            for(group = 0; group < answerCount; ++ group) {
                if(responseGroupArray.includes(group)) {
                    continue;
                }
                responseArray.push(answerList[Math.floor((Math.random() * groupSize)) + group * groupSize]);
            }

            callback(responseArray);
        }
    }
    answerFile.send(null);
}