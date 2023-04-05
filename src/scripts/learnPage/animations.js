document.querySelectorAll('.questionContainer__answerBlock__answer')
    .forEach(element => {
        element.addEventListener('click', () => {
            if ( element.classList.contains('questionContainer__answerBlock__answer--selected')) {
                element.classList.remove('questionContainer__answerBlock__answer--selected');
            } else {
                element.classList.add('questionContainer__answerBlock__answer--selected');
            }
        })
    });