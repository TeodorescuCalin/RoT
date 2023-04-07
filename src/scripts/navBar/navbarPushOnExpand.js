const expandCheckBox = document.getElementById("checkNav");

expandCheckBox.addEventListener('change', element => {
    if ( element.target.checked === true ) {
        document.getElementById("navigationBar").style.marginBottom = "700px";
    } else {
        document.getElementById("navigationBar").style.marginBottom = "0px";
    }
})