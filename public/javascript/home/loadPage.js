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
                    let newElement = document.createElement("li");
                    newElement.innerText = learnData[index].username + ": " + learnData[index].result;
                    newElement.classList.add("rankElement");
                    newElement.style.cursor = "pointer";
                    newElement.onclick = () => { window.location.href = HOST_URL + "profile/" + learnData[index].username };
                    learnRanking.appendChild(newElement);
                }

                let quizRanking = document.getElementById('quizRanks');
                const quizData = response['data']['quiz'];
                for ( let index = 0; index < quizData.length; index ++ ) {
                    let newElement = document.createElement("li");
                    newElement.innerText = quizData[index].username + ": " + quizData[index].result;
                    newElement.style.cursor = "pointer";
                    newElement.onclick = () => { window.location.href = HOST_URL + "profile/" + learnData[index].username }
                    quizRanking.appendChild(newElement);
                }
            })
}


function getRSS () {
    window.location.href = HOST_URL + "ranking_rss";
}