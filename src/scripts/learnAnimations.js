document.querySelectorAll('.questionContainer__answerBlock__answer')
    .forEach(element => {
        element.addEventListener('click', () => {
            if ( $(element).hasClass('questionContainer__answerBlock__answer--selected')) {
                $(element).removeClass('questionContainer__answerBlock__answer--selected');
            } else {
                $(element).addClass('questionContainer__answerBlock__answer--selected');
            }
        })
    })