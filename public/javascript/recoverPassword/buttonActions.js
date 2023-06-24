async function recover() {

    await fetch (
        new Request (
            HOST_URL + "recover_password",
            {
                method : "POST",
                headers : {
                    "Content-Type" : "application/json"
                },
                body : JSON.stringify({
                    "email" : document.getElementById('email').value
                })
            }
        )
    ).then(response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    alert(response.error);
                } else {
                    alert("Parola ta a fost setată să fie la fel ca username-ul");
                }
            }
        )
}