window.addEventListener(
    'resize', getSize, false);

function getSize() {
    let zoom = ((window.outerWidth) / window.innerWidth) * 100;

    if ( zoom >= 250 ) {
        document.getElementById("aboutButton").style.display = 'none';
        document.getElementById("helpButton").style.display = 'none';
    } else {
        document.getElementById("aboutButton").style.display = 'block';
        document.getElementById("helpButton").style.display = 'block';
    }

    if ( zoom >= 175 ) {
        document.getElementById("menuBar").style.display = 'none';
    } else {
        document.getElementById("menuBar").style.display = 'flex';
    }
}