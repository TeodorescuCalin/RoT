window.onload = function() {
    createTable();
}

async function createTable() {
    await fetch (HOST_URL + "users")
        .then (response => response.json())
        .then(
            response => {
                if ( ! response.ok ) {
                    window.location.href ="/public/error"
                }

                const data = response['data'];
                var usersTable = document.getElementById('allUsers');

                for (let i = 0; i < data.length; i++) {
                    const tr = document.createElement("tr");
                    const id = document.createElement("td");
                    const username = document.createElement("td");
                    const lastActivity = document.createElement("td");
                    const deleted = document.createElement("td");

                    id.textContent = data[i].id;
                    username.textContent = data[i].username;
                    lastActivity.textContent = data[i].updated_at;

                    const button = document.createElement("button");
                    const buttonId = 'deleteUserButton' + i;
                    const icon = document.createElement("i");
                    icon.classList.add("fas", "fa-check");
                    icon.setAttribute("aria-hidden", "true");
                    button.id = buttonId;
                    button.appendChild(icon);
                    button.classList.add("deleteButton");
                    button.onclick = function() {
                        deleteUser(data[i].id);
                    }
                    deleted.appendChild(button);

                    tr.appendChild(id);
                    tr.appendChild(username);
                    tr.appendChild(lastActivity);
                    tr.appendChild(deleted);

                    usersTable.appendChild(tr);
                }
            }
        )
}

async function deleteUser(userId) {

    await fetch (
        new Request (
            HOST_URL + "users/" + userId,
            {
                method : "DELETE",
                headers : {
                    "Content-Type" : "application/json"
                }
            }
        )
    )
}