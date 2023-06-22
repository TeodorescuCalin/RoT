function incrementQuestionNumber() {
    var element = document.getElementById('questionNumber');
    var currentText = element.innerText;
    var number = parseInt(currentText.match(/\d+/)[0]);

    if (number == 2)
        toJSON();

    if (number < 25) {
        // questions[number] = questionData();
        var incremented = currentText.replace(number, number + 1);
        element.style.fontWeight = 'bold';
        element.innerText = incremented;
    }
    else if (number == 25) {
        // questions[number] = questionData();
        var incremented = currentText.replace(number, number + 1);
        element.style.fontWeight = 'bold';
        element.innerText = incremented;
        var button = document.getElementById('incrementButton');
        button.innerText = "Creeaza chestionar";
    }
    else if (number == 26) {
        // questions[number] = questionData();
        var button = document.getElementById('incrementButton');
        // button.onclick = toJSON();
        // console.log(JSON.stringify(questions));
    }

    deleteNewDivs();
    var selectElement = document.getElementById("correctAnswersNumber");
    selectElement.value = "notSelected";
    // de verificat daca poate da next (daca a completat toate campurile)
}

function decrementQuestionNumber() {
    var element = document.getElementById('questionNumber');
    var currentText = element.innerText;
    var number = parseInt(currentText.match(/\d+/)[0]);

    if (number == 26) {
        var decremented = currentText.replace(number, number - 1);
        element.style.fontWeight = 'bold';
        element.innerText = decremented;
        var button = document.getElementById('incrementButton');
        button.innerText = "Inainte";

        deleteNewDivs();
        var selectElement = document.getElementById("correctAnswersNumber");
        selectElement.value = "notSelected";
    }
    else if (number == 1) {
        // var button = document.getElementById('decrementButton');
        // button.style.pointerEvents = "none";
        // button.style.opacity = 0.5;
        // button.style.cursor = "not-allowed";
    }
    else if (number > 1) {
        var decremented = currentText.replace(number, number - 1);
        element.style.fontWeight = 'bold';
        element.innerText = decremented;

        deleteNewDivs();
        var selectElement = document.getElementById("correctAnswersNumber");
        selectElement.value = "notSelected";
    }
}

function getNumberFromSelect() {
    var element = document.getElementById("correctAnswersNumber");
    const value = parseInt(element.value, 10);
    return value;
}

function deleteNewDivs() {
    var deleteDivs = document.querySelectorAll('.newDiv');
    if (deleteDivs.length > 0)
        deleteDivs.forEach(function(div) {
            div.remove();
        })
}

function createCorrectAnswerBoxes() {
    deleteNewDivs();
    const boxes = getNumberFromSelect();
    var element = document.getElementById("form-container");
    var decrementButton = document.getElementsByClassName("buttonsContainer")[0];
    for (let i = 0; i < boxes; i++) {
        const div = document.createElement("div");
        div.classList.add("newDiv");
        div.classList.add("inputContainer");

        var label = document.createElement("label");
        label.textContent = "Raspunsul corect " + (i + 1);
        label.style.fontWeight = 'bold';
        div.append(label);

        var icon = document.createElement("i");
        icon.classList.add("fas", "fa-check");
        div.append(icon);

        var input = document.createElement("input");
        input.id = "newDiv" + i;
        input.type = "text";
        input.placeholder = "Introdu textul";
        div.append(input);

        element.insertBefore(div, decrementButton);
    }
}

function createWrongAnswerBoxes() {
    const boxes = 3 - getNumberFromSelect();
    var element = document.getElementById("form-container");
    var decrementButton = document.getElementsByClassName("buttonsContainer")[0];
    for (let i = 0; i < boxes; i++) {
        const div = document.createElement("div");
        div.classList.add("newDiv");
        div.classList.add("inputContainer");

        var label = document.createElement("label");
        label.textContent = "Raspunsul gresit " + (i + 1);
        label.style.fontWeight = 'bold';
        div.append(label);

        var icon = document.createElement("i");
        icon.classList.add("fa-solid", "fa-x");
        div.append(icon);

        var input = document.createElement("input");
        input.id = "newDiv" + (3 - boxes + i);
        input.type = "text";
        input.placeholder = "Introdu textul";
        div.append(input);

        element.insertBefore(div, decrementButton);

    }

}

function getQuestionNumber() {
    var element = document.getElementById('questionNumber');
    var currentText = element.innerText;
    var number = parseInt(currentText.match(/\d+/)[0]);
    return number;
}

var questionsData = [];
var savedQuestions = [];
var savedPaths = [];
var savedCorectAnswersNumber = [];
var savedNewDivs = [];

function saveQuestion() {
    var questionElement = document.getElementById('questionText');
    var question = questionElement.value;
    var found = false;
    savedQuestions.forEach(function (obj) {
        if (obj.index == getQuestionNumber()) {
            obj.text = question;
            found = true;
        }
    });
    if (found == false)
        savedQuestions.push({index: getQuestionNumber(), text: question});
    questionElement.value = '';
}

function getQuestion() {
    var questionElement = document.getElementById('questionText');
    savedQuestions.forEach(function (obj) {
        if (obj.index == getQuestionNumber())
            questionElement.value = obj.text;
    });
}

function savePath() {
    var pathElement = document.getElementById('questionImage');
    var path = pathElement.value;
    var found = false;
    savedPaths.forEach(function (obj) {
        if (obj.index == getQuestionNumber()) {
            obj.text = path;
            found = true;
        }
    });
    if (found == false)
        savedPaths.push({index: getQuestionNumber(), text: path});
    pathElement.value = '';
}

function getPath() {
    var pathElement = document.getElementById('questionImage');
    savedPaths.forEach(function (obj) {
        if (obj.index == getQuestionNumber()) {
            pathElement.value = obj.text;
        }
    });
}

function saveCorrectAnswersNumber() {
    var selectElement = document.getElementById('correctAnswersNumber');
    var answers = getNumberFromSelect();
    var found = false;
    savedCorectAnswersNumber.forEach(function (obj) {
        if (obj.index == getQuestionNumber()) {
            if (isNaN(answers))
                obj.value = "notSelected";
            else
                obj.value = answers;
            found = true;
        }
    });
    if (found == false)
        if (isNaN(answers))
            savedCorectAnswersNumber.push({index: getQuestionNumber(), value: "notSelected"});
        else
            savedCorectAnswersNumber.push({index: getQuestionNumber(), value: answers});
    selectElement.value = "notSelected";
}

function getCorrectAnswersNumber() {
    var selectElement = document.getElementById('correctAnswersNumber');
    savedCorectAnswersNumber.forEach(function (obj) {
        if (obj.index == getQuestionNumber())
            selectElement.value = obj.value;
    });
}

function saveNewDivs() {
    var newDivsCollection = document.getElementsByClassName('newDiv');
    var newDivs = Array.from(newDivsCollection);
    var index = 0;
    newDivs.forEach(function () {
        var answerElement = document.getElementById('newDiv' + index);
        var answer = answerElement.value;
        var found = false;
        savedNewDivs.forEach(function (obj) {
            if (obj.questionNumber == getQuestionNumber() && obj.index == index) {
                obj.text = answer;
                found = true;
            }
        });
        if (found == false)
            savedNewDivs.push({questionNumber: getQuestionNumber(), index: index, text: answer});
        answerElement.value = '';
        index++;
    })
}

function getNewDivs() {
    var newDivsCollection = document.getElementsByClassName('newDiv');
    var newDivs = Array.from(newDivsCollection);
    // console.log(newDivs);
    var index = 0;
    newDivs.forEach(function () {
        var answerElement = document.getElementById('newDiv' + index);
        savedNewDivs.forEach(function (obj) {
            if (obj.questionNumber == getQuestionNumber() && obj.index == index)
                answerElement.value = obj.text;
        })

        index++;
    });
}

function saveInputValues() {
    saveQuestion();
    savePath();
    saveCorrectAnswersNumber();
    saveNewDivs();
}

function getInputValues() {
    getQuestion();
    getPath();
    getCorrectAnswersNumber();
    getNewDivs();
}

function loadFunctionsDecrement() {
    saveInputValues();
    decrementQuestionNumber();
    getInputValues();
    createCorrectAnswerBoxes();
    createWrongAnswerBoxes();
    getInputValues();
}

function loadFunctionsIncrement() {
    saveInputValues();
    incrementQuestionNumber();
    getInputValues();
    createCorrectAnswerBoxes();
    createWrongAnswerBoxes();
    getInputValues();
}

function modify_loadFunctionsDecrement() {
    // getInputValues();
    // mai intai load la intrebari, rasp, etc
}

function modify_loadFunctionsIncrement() {
    //
}

function createCorrectAnswerBoxesQuestionType() {
    deleteNewDivs();

    var typeElement = document.getElementById("questionType");
    var boxes = 1;
    if (typeElement.value == 'multipleChoice' || typeElement.value == 'notSelected')
        boxes = getNumberFromSelect();
    var element = document.getElementById("form-container");
    var decrementButton = document.getElementsByClassName("buttonsContainer")[0];
    for (let i = 0; i < boxes; i++) {
        const div = document.createElement("div");
        div.classList.add("newDiv");
        div.classList.add("inputContainer");

        var label = document.createElement("label");
        label.textContent = "Raspunsul corect " + (i + 1);
        label.style.fontWeight = 'bold';
        div.append(label);

        var icon = document.createElement("i");
        icon.classList.add("fas", "fa-check");
        div.append(icon);

        var input = document.createElement("input");
        input.id = "newDiv" + i;
        input.type = "text";
        input.placeholder = "Introdu textul";
        div.append(input);

        element.insertBefore(div, decrementButton);
    }
}

function selectAnswersWithType() {
    select = document.getElementById('correctAnswersNumber');
    type = document.getElementById('questionType');
    if (type.value == 'count') {
        select.value = 'notSelected';
        select.remove(2);
        select.remove(2);
    }
    else if (type.value == 'multipleChoice' && select.options.length < 3) {
        select.value = 'notSelected';
        let newOption2 = new Option('2', 2);
        let newOption3 = new Option('3', 3);
        select.add(newOption2, undefined);
        select.add(newOption3, undefined);
    }
}

// function questionData() {
//     textElement = document.getElementById('questionText');
//     imageElement = document.getElementById('questionImage');
//     correctAnswersNumberElement = document.getElementById('correctAnswersNumber');

//     text = textElement.value;
//     image = imageElement.value;
//     correctAnswersNumber = correctAnswersNumberElement.value;

//     correctAnswers = [];
//     wrongAnswers = [];

//     for (let i = 0; i < 3; i++) {
//         var newDiv = document.getElementById('newDiv' + i);
//         if (i < correctAnswersNumber)
//             correctAnswers.push(newDiv.value);
//         else
//             wrongAnswers.push(newDiv.value);
//     }

//     var data = {
//         text: text,
//         image: image,
//         correctAnswersNumber: correctAnswersNumber,
//         correctAnswers: correctAnswers,
//         wrongAnswers: wrongAnswers
//     };

//     var json = JSON.stringify(data);
//     console.log(json);
//     return data;
// }

function toJSON() {
    for (let i = 0; i < savedQuestions.length; i++) {
        // correctAnswers = [];
        // wrongAnswers = [];

        // for (let j = 0; j < 3; j++) {
        //     if (j < savedCorectAnswersNumber[i].value)
        //         correctAnswers.push(savedNewDivs[i * 3 + j].text);
        //     else
        //         wrongAnswers.push(savedNewDivs[i * 3 + j].text);
        // }

        firstAnswer = savedNewDivs[i * 3 + 0].text;
        secondAnswer = savedNewDivs[i * 3 + 1].text;
        thirdAnswer = savedNewDivs[i * 3 + 2].text;

        correct = savedCorectAnswersNumber[i].value;
        if (correct == 1) {
            firstAnswerCorrect = true;
            secondAnswerCorrect = false;
            thirdAnswerCorrect = false;
        }
        else if (correct == 2) {
            firstAnswerCorrect = true;
            secondAnswerCorrect = true;
            thirdAnswerCorrect = false;
        }
        else {
            firstAnswerCorrect = true;
            secondAnswerCorrect = true;
            thirdAnswerCorrect = true;
        }

        // var data = {
        //     text: savedQuestions[i].text,
        //     image: savedPaths[i].text,
        //     correctAnswersNumber: savedCorectAnswersNumber[i].value,
        //     correctAnswers: correctAnswers,
        //     wrongAnswers: wrongAnswers
        // };

        var data = {
            text: savedQuestions[i].text,
            image: savedPaths[i].text,
            firstAnswer: firstAnswer,
            firstAnswerCorrect: firstAnswerCorrect,
            secondAnswer: secondAnswer,
            secondAnswerCorrect: secondAnswerCorrect,
            thirdAnswer: thirdAnswer,
            thirdAnswerCorrect: thirdAnswerCorrect
        };

        questionsData.push(data);
    }
    json = JSON.stringify(questionsData);
    console.log(json);
}

// window.addEventListener('beforeunload', function(event) {
//     event.preventDefault();
// });