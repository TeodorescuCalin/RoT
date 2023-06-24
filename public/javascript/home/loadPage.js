async function getTips () {

    await fetch ( HOST_URL + "tips" )
        .then(response => response.json())
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location.href ="/public/error"
                }

                let tipList = document.getElementById('tipList');
                for ( let index = 0; index < response['data'].length; index ++ ) {
                    let newTip = document.createElement("li");
                    newTip.innerText = "• " + response['data'][index].text;
                    tipList.appendChild(newTip);
                }
            })
}


async function getRanking () {
    await fetch ( HOST_URL + "ranking" )
        .then(response => response.json())
        .then(
            response => {


                if ( ! response.ok ) {
                    window.location.href ="/public/error"
                }

                let learnRanking = document.getElementById('learningRanks');
                const learnData = response['data']['learn'];
                for ( let index = 0; index < learnData.length; index ++ ) {
                    let newTip = document.createElement("li");
                    newTip.innerText = learnData[index].username + ": " + learnData[index].result;
                    learnRanking.appendChild(newTip);
                }

                let quizRanking = document.getElementById('quizRanks');
                const quizData = response['data']['quiz'];
                for ( let index = 0; index < quizData.length; index ++ ) {
                    let newTip = document.createElement("li");
                    newTip.innerText = quizData[index].username + ": " + quizData[index].result;
                    quizRanking.appendChild(newTip);
                }
            })
}