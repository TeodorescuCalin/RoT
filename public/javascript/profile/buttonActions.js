async function logout () {
    localStorage.removeItem("username");

    await fetch (
        HOST_URL + "logout",
        {
            method : "POST",
            headers : {
                "Content-Type" : "application/json"
            }
        }
    ).then( response => response.json() )
        .then(
            response => {

                if ( ! response.ok ) {
                    window.location.href = HOST_URL + "error";
                } else {
                    window.location.href = HOST_URL;
                }
            }
        )
}

function resetPassword () {
    window.location.href = HOST_URL + "resetPassword";
}