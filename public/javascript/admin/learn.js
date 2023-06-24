function loadQuestionNumber() {
    element = document.getElementById('questionNumber');
    window.location.href =HOST_URL + "modifyQuestion/" + document.getElementById('questionNumber').value;
}

async function postNewQuestion() {
    const textElement = document.getElementById('questionText')
    const imageElement = document.getElementById('questionImage');
    const explanationElement = document.getElementById('questionExplanation');
    const categoryElement = document.getElementById('questionCategory');
    const typeElement = document.getElementById('questionType');
    const answersNumberElement = document.getElementById('correctAnswersNumber');
    const totalAnswersElement = document.getElementById('answersNumber');

    const text = textElement.value;
    const image = imageElement.value;
    const explanation = explanationElement.value;
    const category = categoryElement.value;
    const type = typeElement.value;
    const answersNumber = answersNumberElement.value;
    const totalAnswersNumber = totalAnswersElement.value;
    let answersIds = [];
    if (type == 'count') {
        for (let i = 0; i < 2 * answersNumber; i += 2) {
            let newDiv = document.getElementById('newDiv' + i);
            let newDivCount = document.getElementById('newDiv' + (i + 1));
            answersIds.push(
                {
                    "text": newDiv.value,
                    "count": newDivCount.value
            });
        }
    }
    else if (type == 'multipleChoice') {
        for (let i = 0; i < answersNumber; i++) {
            let newDiv = document.getElementById('newDiv' + i);
            answersIds.push({"text": newDiv.value});
        }
    }

    const values = {
        text: text,
        image_path: image,
        explanation: explanation,
        category: category,
        type: type,
        answer_count: totalAnswersNumber,
        answers: answersIds
    };

    var json = JSON.stringify(values);

    await fetch (
        new Request (
            HOST_URL + "learn/questions",
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

async function loadQuestion() {
    const questionId = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
    await fetch (HOST_URL + "learn/questions/" + questionId)
        .then (response => response.json())
        .then(
            response => {
                if ( ! response.ok ) {
                    window.location.href ="/public/error"
                }

                const data = response['data'];
                var textElement = document.getElementById('questionText');
                var imageElement = document.getElementById('questionImage');
                var explanationElement = document.getElementById('questionExplanation');
                var categoryElement = document.getElementById('questionCategory');
                var typeElement = document.getElementById('questionType');
                var correctAnswersElement = document.getElementById('correctAnswersNumber');

                textElement.value = data.text;
                imageElement.value = data.image_path;
                explanationElement.value = data.explanation;
                categoryElement.value = data.category;
                typeElement.value = data.type;
                correctAnswersElement.value = data.answers.length;
            }
        )
}