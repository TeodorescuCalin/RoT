function loadQuestionNumber(url) {
    element = document.getElementById('questionNumber');
    const value = parseInt(element.value, 10);
    // verificare daca este exista in bd
    window.location.href = url;
}

async function toJSON() {
    textElement = document.getElementById('questionText')
    imageElement = document.getElementById('questionImage');
    explanationElement = document.getElementById('questionExplanation');
    categoryElement = document.getElementById('questionCategory');
    typeElement = document.getElementById('questionType');
    answersNumberElement = document.getElementById('correctAnswersNumber');

    text = textElement.value;
    image = imageElement.value;
    explanation = explanationElement.value;
    category = categoryElement.value;
    type = typeElement.value;
    answersNumber = answersNumberElement.value;
    answersIds = [];
    for (let i = 0; i < answersNumber; i++) {
        var newDiv = document.getElementById('newDiv' + i);
        answersIds.push(newDiv.value);
    }


    var values = {
        text: text,
        image_path: image,
        explanation: explanation,
        category: category,
        type: type,
        answerCount: answersNumber,
        answers: answersIds
    };

    var json = JSON.stringify(values);

    await fetch (
        new Request (
            "",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : json
            }
        )
    )
}