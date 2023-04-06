document.querySelectorAll('.questionContainer__answerBlock__answer--choice')
    .forEach(element => {
        element.addEventListener('click', () => {
            if ( element.classList.contains('questionContainer__answerBlock__answer--choice--selected')) {
                element.classList.remove('questionContainer__answerBlock__answer--choice--selected');
            } else {
                element.classList.add('questionContainer__answerBlock__answer--choice--selected');
            }
        })
    });