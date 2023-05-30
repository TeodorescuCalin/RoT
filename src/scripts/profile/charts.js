var category_xValues = ["Mechanics", "First Aid", "Signs", "Police", "Speed",
"Priority", "Fines", "General"];
var category_yValues = [55, 49, 44, 24, 15, 22, 31, 44];
var category_barColors = ["red", "green","blue","orange","salmon", "black", "white", "purple"];
var category_sum = 0;
for (var i = 0; i < category_yValues.length; i++)
    category_sum += category_yValues[i];
document.getElementById("totalLearn").innerHTML = "În total, ai răspuns corect la " + category_sum + " întrebări.";


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


var currentDate = new Date();
var currentDay = currentDate.getDate();
var oneWeekAgo = currentDay - 7;
var twoWeeksAgo = oneWeekAgo - 7;
var threeWeeksAgo = twoWeeksAgo - 7;
var fourWeeksAgo = threeWeeksAgo - 7;
var currentWeek = oneWeekAgo + "-" + currentDay;
var lastWeek = twoWeeksAgo + "-" + oneWeekAgo;
var last2Weeks = threeWeeksAgo + "-" + twoWeeksAgo;
var last3Weeks = fourWeeksAgo + "-" + threeWeeksAgo;

var learnProgress_xValues = [last3Weeks, last2Weeks, lastWeek, currentWeek];
var learnProgress_yValues = [100, 30, 400, 50];
var learnProgress_sum = 0;
for (var i = 0; i < learnProgress_yValues.length; i++)
    learnProgress_sum += learnProgress_yValues[i];
var learnProgress_mean = learnProgress_sum / learnProgress_yValues.length;
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

var quizProgress_xValues = [last3Weeks, last2Weeks, lastWeek, currentWeek];
var quizProgress_yValues = [5, 30, 0, 16];
var quizProgress_sum = 0;
for (var i = 0; i < quizProgress_yValues.length; i++)
    quizProgress_sum += quizProgress_yValues[i];
var quizProgress_mean = quizProgress_sum / quizProgress_yValues.length;
document.getElementById("quizMean").innerHTML = "În medie, ai rezolvat aproximativ " + Math.floor(quizProgress_mean) + " chestionare pe zi.";

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