async function login () {
    await fetch (
        new Request (
            HOST_URL + "login",
            {
                    headers : {
                        "Content-Type" : "application/json"
                    },
                    method : "POST",
                    body : JSON.stringify({
                        "username" : document.getElementById('username').value,
                        "password" : document.getElementById('password').value
                    })
                }
        )
    ).then ( response => response.json() )
        .then (
            response => {

                if ( ! response.ok ) {
                    alert(response.error);
                    return;
                }

                localStorage['username'] = response['data']['username'];
                window.location.href = HOST_URL;
                // window.location.reload();
            }
        );
}