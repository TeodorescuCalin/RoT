let category_xValues = [];
let category_yValues = [];
let category_barColors = ["red", "green","blue","orange","salmon", "black", "white", "purple"];
let category_sum = 0;



const currentDate = new Date();
const currentDay = currentDate.getDate();
const oneWeekAgo = currentDay - 7;
const twoWeeksAgo = oneWeekAgo - 7;
const threeWeeksAgo = twoWeeksAgo - 7;
const fourWeeksAgo = threeWeeksAgo - 7;
const currentWeek = oneWeekAgo + "-" + currentDay;
const lastWeek = twoWeeksAgo + "-" + oneWeekAgo;
const last2Weeks = threeWeeksAgo + "-" + twoWeeksAgo;
const last3Weeks = fourWeeksAgo + "-" + threeWeeksAgo;

let learnProgress_xValues = [last3Weeks, last2Weeks, lastWeek, currentWeek];
let learnProgress_yValues = [];
let learnProgress_sum = 0;
let learnProgress_mean;


let quizProgress_xValues = [last3Weeks, last2Weeks, lastWeek, currentWeek];
let quizProgress_yValues = [];
let quizProgress_sum = 0;
let quizProgress_mean;

const handleResponse = response => {
    if ( response.ok ) {
        return response.json();
    }
    throw new Error( response.status );
}

async function getUserInfo() {
    await fetch(
        new Request(
            'http://localhost:80/rot/public/' + document.getElementById('userId').value
        )
    ).then(handleResponse)
        .then(
            response => {
                console.log(response);
                document.getElementById('nameSurname')
                    .innerText = response.name + " " + response.surname;
                document.getElementById('solvedQuizzesStats')
                    .innerText = response.statistics.quizProgress + "%";
                document.getElementById('solvedLearningQuestionsStats')
                    .innerText = response.statistics.learnProgress + "%";
                document.getElementById('userName')
                    .innerText = "Nume:" + response.name;
                document.getElementById('userSurname')
                    .innerText = "Prenume:" + response.surname;
                document.getElementById('userUsername')
                    .innerText = "Nume de utilizator:" + response.username;
                document.getElementById('userRegisterDate')
                    .innerText = "Dată înregistrare:" + response.created_at.split(' ')[0];

                for ( let stat of response.statistics.statsByCategory ) {
                    category_xValues.push(stat.category);
                    category_yValues.push(stat.count);
                    category_sum += stat.count;
                }
                drawCategoryStatsChart();

                for ( let stat of response.statistics.learnWeeklyStats ) {
                    learnProgress_yValues.push(stat.count);
                    learnProgress_sum += stat.count;
                }
                console.log(learnProgress_sum / learnProgress_yValues.length);
                learnProgress_mean = learnProgress_sum / learnProgress_yValues.length;

                for ( let stat of response.statistics.quizWeeklyStats ) {
                    quizProgress_yValues.push(stat.count);
                    quizProgress_sum += stat.count;
                }
                quizProgress_mean = quizProgress_sum / quizProgress_yValues.length;

                drawProgressCharts();
            }
        ).catch(
            error => console.log(error)
        )
}

function drawCategoryStatsChart() {
    new Chart("categoryChart", {
        type: "bar",
        data: {
            labels: category_xValues,
            datasets: [{
                backgroundColor: category_barColors,
                data: category_yValues
            }]
        },
        options: {
            legend: { display: false },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                text: "Întrebări rezolvate per categorie",
                fontSize: 16,
                color: "white"
            }
        }
    });
    document.getElementById("totalLearn").innerHTML = "În total, ai răspuns corect la " + category_sum + " întrebări.";
}


function drawProgressCharts() {

    document.getElementById("learnMean").innerHTML = "În medie, ai rezolvat aproximativ " + Math.floor(learnProgress_mean) + " întrebări pe săptămână.";
    new Chart("learnProgressChart", {
        type: "line",
        data: {
            labels: learnProgress_xValues,
            datasets: [{
                fill: false,
                pointRadius: 1,
                borderColor: "#0088a9",
                data: learnProgress_yValues
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                text: "Progres întrebări rezolvate în ultima lună",
                fontSize: 16,
                color: 'white'
            }
        }
    });

    document.getElementById("quizMean").innerHTML = "În medie, ai rezolvat aproximativ " + Math.floor(quizProgress_mean) + " chestionare pe săptămână.";
    new Chart("quizProgressChart", {
        type: "line",
        data: {
            labels: quizProgress_xValues,
            datasets: [{
                fill: false,
                pointRadius: 1,
                borderColor: "#0088a9",
                data: quizProgress_yValues
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            },
            title: {
                display: true,
                text: "Progres chestionare rezolvate în ultima lună",
                fontSize: 16,
                color: "white"
            }
        }
    });
}

function secondsToMinSec(input) {
    // var result = [];
    // for (var i = 0; i < input.length; i++) {
        var min = Math.floor(input / 60);
        var sec = input % 60;
        if (sec < 10)
            sec = '0' + sec;
        // result.push(value);
    // }
    var result = min + ":" + sec;
    return result;
}

var quizDuration_xValues = [10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
var quizDuration_yValues_seconds = [100, 170, 160, 170, 140, 130, 160, 174, 1304, 1523];
var quizDuration_yValues = {
    0 : '0:00',
    300 : '5:00',
    600 : '10:00',
    900 : '15:00',
    1200 : '20:00',
    1500: '25:00',
    1800: '30:00'
};

var quizDuration_sum = 0;
for (var i = 0; i < quizDuration_yValues_seconds.length; i++)
    quizDuration_sum += quizDuration_yValues_seconds[i];
var quizDuration_mean = quizDuration_sum / quizDuration_yValues_seconds.length;
if (quizDuration_mean > 1500)
    document.getElementById("quizDuration").innerHTML = "În medie, ți-a luat " + secondsToMinSec(Math.floor(quizDuration_mean)) + 
    " minute să rezolvi un chestionar. Aproape rămâi fără timp. Întoarce-te la mediul de învățare pentru a te pregăti mai bine.";
else
    document.getElementById("quizDuration").innerHTML = "În medie, ți-a luat " + secondsToMinSec(Math.floor(quizDuration_mean)) + " minute să rezolvi un chestionar.";

var quizDuration_yValues_formatted = secondsToMinSec(quizDuration_yValues_seconds);
// var quizDuration_yValues_formatted = ["21:10", "22:10"];

new Chart("quizDurationChart", {
    type: "line",
    data: {
        labels: quizDuration_xValues,
        datasets: [{
            // fill: false,
            // pointRadius: 1,
            borderColor: "#0088a9",
            data: quizDuration_yValues_seconds,
            tension: 0.01
        }]
    },
    options: {
    legend: {display: false},
    scales: {
        yAxes: [{
        ticks: {
            beginAtZero: true,
            callback: function(value, index) {
                return quizDuration_yValues[value];
            }
        }
        }]
    },
    title: {
        display: true,
        text: "Durata ultimelor 10 chestionare",
        fontSize: 16,
        color: "white"
    }
}
});