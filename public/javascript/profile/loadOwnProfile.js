async function fillProfile () {
    await getUserInfo();

    document.getElementById('solvedQuizzesStats')
        .innerText = userData.statistics.quizProgress + "%";
    document.getElementById('solvedLearningQuestionsStats')
        .innerText = userData.statistics.learnProgress + "%";
    document.getElementById('userName')
        .innerText = "Nume: " + userData.name;
    document.getElementById('userSurname')
        .innerText = "Prenume: " + userData.surname;
    document.getElementById('userUsername')
        .innerText = "Nume de utilizator: " + userData.username;
    document.getElementById('userRegisterDate')
        .innerText = "Dată înregistrare: " + userData.created_at.split(' ')[0];
    document.getElementById('totalQuestions')
        .innerText = "Întrebări parcurse: " + userData.statistics['learnStatistics'].total;
    document.getElementById('passedQuestions')
        .innerText = "Întrebări răspunse corect: " + userData.statistics['learnStatistics'].passed;
    document.getElementById('failedQuestions')
        .innerText = "Întrebări răspunse greșit: " + userData.statistics['learnStatistics'].failed;

    document.getElementById('quiz_total').innerText = "Chestionare parcurse: " + userData.statistics['quizStatistics'].total;
    document.getElementById('quiz_failed').innerText = "Chestionare promovate: " + userData.statistics['quizStatistics'].failed;
    document.getElementById('quiz_passed').innerText = "Chestionare perfecte: " + userData.statistics['quizStatistics'].passed;
    document.getElementById('quiz_perfect').innerText = "Chestionare picate: " + userData.statistics['quizStatistics'].perfect;
    document.getElementById('quiz_average_duration').innerText = "Timp mediu de rezolvare: " + Math.floor(userData.statistics['quizStatistics'].average_duration / 60) + ":" + userData.statistics['quizStatistics'].average_duration % 60;
    document.getElementById('quiz_shortest_duration').innerText = "Cel mai scurt timp de rezolvare: " + Math.floor(userData.statistics['quizStatistics'].shortest_duration / 60) + ":" + userData.statistics['quizStatistics'].shortest_duration % 60;
    document.getElementById('quiz_longest_duration').innerText = "Cel mai lung timp de rezolvare: " + Math.floor(userData.statistics['quizStatistics'].longest_duration / 60) + ":" + userData.statistics['quizStatistics'].longest_duration % 60;
}