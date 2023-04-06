document.querySelectorAll('.questionContainer__answerBlock__choiceAnswer')
    .forEach(element => {
        element.addEventListener('click', () => {
            if ( element.classList.contains('questionContainer__answerBlock__choiceAnswer--selected')) {
                element.classList.remove('questionContainer__answerBlock__choiceAnswer--selected');
            } else {
                element.classList.add('questionContainer__answerBlock__choiceAnswer--selected');
            }
        })
    });