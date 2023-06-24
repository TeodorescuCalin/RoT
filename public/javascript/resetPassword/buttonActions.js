async function reset() {

    await fetch (
        new Request (
            HOST_URL + "resetPassword",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : JSON.stringify({
                    "oldPassword" : document.getElementById('oldPassword').value,
                    "newPassword" : document.getElementById('newPassword').value
                })
            }
        )
    ).then(response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    alert(response.error);
                } else {
                    alert("Parola ta a fost schimbată");
                }
            }
        )
}