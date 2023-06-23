function loadQuestionNumber(url) {
    element = document.getElementById('questionNumber');
    const value = parseInt(element.value, 10);
    // verificare daca este exista in bd
    window.location.href = url;
}

async function postNewQuestion() {
    const textElement = document.getElementById('questionText')
    const imageElement = document.getElementById('questionImage');
    const explanationElement = document.getElementById('questionExplanation');
    const categoryElement = document.getElementById('questionCategory');
    const typeElement = document.getElementById('questionType');
    const answersNumberElement = document.getElementById('correctAnswersNumber');

    const text = textElement.value;
    const image = imageElement.value;
    const explanation = explanationElement.value;
    const category = categoryElement.value;
    const type = typeElement.value;
    const answersNumber = answersNumberElement.value;
    let answersIds = [];
    for (let i = 0; i < answersNumber; i++) {
        let newDiv = document.getElementById('newDiv' + i);
        answersIds.push({"text" : newDiv.value});

    }


    const values = {
        text: text,
        image_path: image,
        explanation: explanation,
        category: category,
        type: type,
        answer_count: answersNumber,
        answers: answersIds
    };

    var json = JSON.stringify(values);

    let raspuns = await fetch (
        new Request (
            HOST_URL + "learn",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : json
            }
        )
    )
    console.log(raspuns);

    // await fetch (
    //     new Request (
    //         HOST_URL + "register",
    //         {
    //             headers : {
    //                 "Content-Type" : "application/json"
    //             },
    //             method : "POST",
    //             body : JSON.stringify({
    //                 "username" : "asd",
    //                 "name" : "asd",
    //                 "surname" : "asd",
    //                 "email" : "asd",
    //                 "password" : "asd"
    //             })
    //         }
    //     )
    // )
}