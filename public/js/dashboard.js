// Riferimenti agli elementi DOM
const usernameDisplay = document.getElementById("username");
const logoutButton = document.getElementById("logoutButton");
const listsContainer = document.getElementById("lists");
const createListForm = document.getElementById("createListForm");

// Carica il nome utente dalla sessione
const username = sessionStorage.getItem("username");
if (!username) {
    alert("Devi effettuare il login per accedere alla dashboard.");
    window.location.href = "login.html";
} else {
    usernameDisplay.textContent = username;
}

// Funzione per caricare le liste
async function loadLists() {
    try {
        const response = await fetch("../api/lists/read.php");
        const lists = await response.json();

        if (response.ok) {
            listsContainer.innerHTML = ""; // Svuota il contenitore

            lists.forEach((list) => {
                const listItem = document.createElement("div");
                listItem.classList.add("list-item");
                listItem.innerHTML = `
                    <h3>${list.nome_lista} (${list.visibilita})</h3>
                    <button onclick="deleteList(${list.id})">Elimina</button>
                    <button onclick="updateList(${list.id}, '${list.nome_lista}', '${list.visibilita}')">Modifica</button>
                `;
                listsContainer.appendChild(listItem);
            });
        } else {
            listsContainer.innerHTML = `<p>${lists.error || "Errore nel caricamento delle liste."}</p>`;
        }
    } catch (error) {
        listsContainer.innerHTML = `<p>Errore nella comunicazione con il server.</p>`;
    }
}

// Funzione per creare una nuova lista
createListForm.addEventListener("submit", async (e) => {
    e.preventDefault();

    const nome_lista = document.getElementById("listName").value;
    const visibilita = document.getElementById("visibility").value;

    try {
        const response = await fetch("../api/lists/create.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nome_lista, visibilita }),
        });

        const result = await response.json();

        if (response.ok) {
            alert("Lista creata con successo!");
            loadLists(); // Ricarica le liste
            createListForm.reset(); // Resetta il form
        } else {
            alert(result.error || "Errore durante la creazione della lista.");
        }
    } catch (error) {
        alert("Errore nella comunicazione con il server.");
    }
});

// Funzione per eliminare una lista
async function deleteList(id) {
    if (!confirm("Sei sicuro di voler eliminare questa lista?")) return;

    try {
        const response = await fetch("../api/lists/delete.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_lista: id }),
        });

        const result = await response.json();

        if (response.ok) {
            alert("Lista eliminata con successo!");
            loadLists(); // Ricarica le liste
        } else {
            alert(result.error || "Errore durante l'eliminazione della lista.");
        }
    } catch (error) {
        alert("Errore nella comunicazione con il server.");
    }
}

// Funzione per aggiornare una lista
function updateList(id, nome_corrente, visibilita_corrente) {
    const nuovo_nome = prompt("Modifica il nome della lista:", nome_corrente);
    const nuova_visibilita = confirm("Rendi la lista pubblica?") ? "pubblica" : "privata";

    if (nuovo_nome) {
        fetch("../api/lists/update.php", {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                id_lista: id,
                nome_lista: nuovo_nome,
                visibilita: nuova_visibilita,
            }),
        })
            .then((response) => response.json())
            .then((result) => {
                if (response.ok) {
                    alert("Lista aggiornata con successo!");
                    loadLists();
                } else {
                    alert(result.error || "Errore durante l'aggiornamento della lista.");
                }
            })
            .catch((error) => {
                alert("Errore nella comunicazione con il server.");
            });
    }
}

// Funzione per il logout
logoutButton.addEventListener("click", async () => {
    try {
        const response = await fetch("../api/users/logout.php", { method: "POST" });
        if (response.ok) {
            alert("Logout avvenuto con successo!");
            sessionStorage.clear();
            window.location.href = "login.html";
        } else {
            alert("Errore durante il logout.");
        }
    } catch (error) {
        alert("Errore nella comunicazione con il server.");
    }
});

// Carica le liste all'avvio
loadLists();
