
loadPage();

async function loadPage () {
    await fetch ( HOST_URL + "learn/question" )
        .then( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location = "/public/error"
                }

                const data = response['data'];
                document.getElementById('questionTitle').innerText = data['text'];
                if ( data['image_path'] ) {
                    document.getElementById("questionImage").src = data['image_path'];
                }
                document.getElementById("questionId").value = data['id'];
                document.getElementById("questionType").value = data['type'];
                document.getElementById("questionExplanationHidden").value = data['explanation'];
                if ( data['type'] === "multipleChoice" ) {
                    createNewMultipleChoiceDiv(data['answers']);
                } else {
                    createNewCountDiv(data['answers']);
                }
            }
        )
}

function newMultipleChoiceAnswer(answer, answerDivIndex) {

    let newAnswerDiv = document.createElement("div");
    newAnswerDiv.id = "answer" + answer['id'];
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
    newAnswerDiv.appendChild(document.createTextNode(answer['text']));

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
        answer.id = "answer" + answers[index]['id'];
        answer.classList.add("questionContainer__answerBlock__answer");
        answer.classList.add("questionContainer__answerBlock__answer--count");

        let label = document.createElement("label");
        label.for = "answer" + index + "Count";
        label.innerText = answers[index]['text'];

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