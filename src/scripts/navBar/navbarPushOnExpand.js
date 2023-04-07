const expandCheckBox = document.getElementById("checkNav");

expandCheckBox.addEventListener('change', element => {
    if ( element.target.checked === true ) {
        document.getElementById("navigationBar").style.marginBottom = "500px";
    } else {
        document.getElementById("navigationBar").style.marginBottom = "200px";
    }
})