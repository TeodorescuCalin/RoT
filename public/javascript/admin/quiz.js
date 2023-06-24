function incrementQuestionNumber() {
    var element = document.getElementById('questionNumber');
    var currentText = element.innerText;
    var number = parseInt(currentText.match(/\d+/)[0]);

    if (number < 25) {
        var incremented = currentText.replace(number, number + 1);
        element.style.fontWeight = 'bold';
        element.innerText = incremented;
    }
    else if (number == 25) {
        var incremented = currentText.replace(number, number + 1);
        element.style.fontWeight = 'bold';
        element.innerText = incremented;
        var button = document.getElementById('incrementButton');
        button.innerText = "Creeaza chestionar";
    }
    else if (number == 26) {

        createQuestion();
    }

    deleteNewDivs();
    var selectElement = document.getElementById("correctAnswersNumber");
    selectElement.value = "notSelected";
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

function createCorrectAnswerBoxesQuestionType() {
    deleteNewDivs();

    var typeElement = document.getElementById("questionType");
    if (typeElement.value === 'multipleChoice') {
        var boxes = getNumberFromSelect();
        // console.log(boxes);
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
            console.log("asdojhnasjkd");

            element.insertBefore(div, decrementButton);
        }
    }
    else if (typeElement.value === 'count') {
        var boxes  = 2 * getNumberFromSelect();
        var element = document.getElementById("form-container");
        var decrementButton = document.getElementsByClassName("buttonsContainer")[0];
        for (let i = 0; i < boxes; i++) {
            if (i % 2 == 0) {
                const div = document.createElement("div");
                div.classList.add("newDiv");
                div.classList.add("inputContainer");

                var label = document.createElement("label");
                label.textContent = "Semnul " + (i / 2 + 1);
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
            else {
                const div = document.createElement("div");
                div.classList.add("newDiv");
                div.classList.add("inputContainer");

                var label = document.createElement("label");
                label.textContent = "Numărul corespunzator semnului " + ((i-1) / 2 + 1);
                label.style.fontWeight = 'bold';
                div.append(label);

                var icon = document.createElement("i");
                icon.classList.add("fa-solid", "fa-list-ol");
                div.append(icon);

                var input = document.createElement("input");
                input.id = "newDiv" + i;
                input.type = "text";
                input.placeholder = "Introdu numărul";
                div.append(input);

                element.insertBefore(div, decrementButton);

            }
        }

    }
}

function selectAnswersWithType() {
    select = document.getElementById('questionCategory');
    type = document.getElementById('questionType');
    if (type.value == 'count') {
        select.value = 'signs';
        for (let i = 0; i < 8; i++)
            select.remove(1);
    }

    else if (type.value == 'multipleChoice' && select.options.length < 3) {
        select.value = 'notSelected';
        let newOption = new Option('Selecteaza categoria', 'notSelected');
        newOption.disabled = true;
        newOption.selected = true;
        newOption.hidden = true;
        let newOption2 = new Option('Mecanică', 'mechanics');
        let newOption3 = new Option('Prim ajutor', 'firstAid');
        let newOption4 = new Option('Poliție', 'police');
        let newOption5 = new Option('Limite de viteză', 'speed');
        let newOption6 = new Option('Reguli de prioritate', 'priority');
        let newOption7 = new Option('Amenzi, sancțiuni', 'fines');
        let newOption8 = new Option('General', 'general');

        select.add(newOption, undefined);
        select.add(newOption2, undefined);
        select.add(newOption3, undefined);
        select.add(newOption4, undefined);
        select.add(newOption5, undefined);
        select.add(newOption6, undefined);
        select.add(newOption7, undefined);
        select.add(newOption8, undefined);
    }
}

async function createQuestion() {
    for (let i = 0; i < savedQuestions.length; i++) {
        const firstAnswer = savedNewDivs[i * 3 + 0].text;
        const secondAnswer = savedNewDivs[i * 3 + 1].text;
        const thirdAnswer = savedNewDivs[i * 3 + 2].text;

        let firstAnswerCorrect;
        let secondAnswerCorrect;
        let thirdAnswerCorrect;
        const correct = savedCorectAnswersNumber[i].value;
        if (correct === 1) {
            firstAnswerCorrect = true;
            secondAnswerCorrect = false;
            thirdAnswerCorrect = false;
        }
        else if (correct === 2) {
            firstAnswerCorrect = true;
            secondAnswerCorrect = true;
            thirdAnswerCorrect = false;
        }
        else {
            firstAnswerCorrect = true;
            secondAnswerCorrect = true;
            thirdAnswerCorrect = true;
        }

        let array = [];
        array.push(
            {
                "text" : firstAnswer,
                "correct" : firstAnswerCorrect
            }
        )
        array.push(
            {
                "text" : secondAnswer,
                "correct" : secondAnswerCorrect
            }
        )
        array.push(
            {
                "text" : thirdAnswer,
                "correct" : thirdAnswerCorrect
            }
        )

        const data = {
            text : savedQuestions[i].text,
            image_path : savedPaths[i].text,
            answers : array
        };

        questionsData.push(data);
    }

    await fetch (
        new Request( HOST_URL + "quiz",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : JSON.stringify({"questions" : questionsData})
            }
        )
    ).then ( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location = HOST_URL + "error";
                }
            }
        )
}

// window.addEventListener('beforeunload', function(event) {
//     event.preventDefault();
// });