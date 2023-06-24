async function register () {
    await fetch (
        new Request (
            HOST_URL + "register",
            {
                headers : {
                    "Content-Type" : "application/json"
                },
                method : "POST",
                body : JSON.stringify({
                    "username" : document.getElementById('username').value,
                    "name" : document.getElementById('name').value,
                    "surname" : document.getElementById('surname').value,
                    "email" : document.getElementById('email').value,
                    "password" : document.getElementById('password').value
                })
            }
        )
    ).then ( response => response.json() )
        .then (
            response => {

                if ( ! response.ok ) {
                    alert ( response.error );
                } else {
                    window.location.href = HOST_URL;
                }
            }
        );
}