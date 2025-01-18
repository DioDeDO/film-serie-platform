document.getElementById("backToDashboard").addEventListener("click", () => {
    window.location.href = "dashboard.html";
});

// Funzione per caricare i contenuti
async function loadExploreContent() {
    const exploreContainer = document.getElementById("exploreContent");

    try {
        const response = await fetch("../api/explore/get_content.php");
        const content = await response.json();

        if (response.ok) {
            exploreContainer.innerHTML = ""; // Svuota il contenitore

            content.forEach((item) => {
                const contentItem = document.createElement("div");
                contentItem.classList.add("content-item");
                contentItem.innerHTML = `
                    <h4>${item.titolo} (${item.anno})</h4>
                    <p>${item.descrizione}</p>
                    <small>Genere: ${item.genere}</small>
                    <button onclick="addToList(${item.id})">Aggiungi alla Lista</button>
                `;
                exploreContainer.appendChild(contentItem);
            });
        } else {
            exploreContainer.innerHTML = `<p>${content.error || "Errore nel caricamento dei contenuti."}</p>`;
        }
    } catch (error) {
        exploreContainer.innerHTML = `<p>Errore nella comunicazione con il server.</p>`;
    }
}

// Funzione per aggiungere un contenuto alla lista
async function addToList(contentId) {
    const listId = prompt("Inserisci l'ID della lista a cui aggiungere il contenuto:");
    if (!listId) return;

    try {
        const response = await fetch("../api/lists/add_item.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ list_id: listId, movie_id: contentId }),
        });

        const result = await response.json();

        if (response.ok) {
            alert("Contenuto aggiunto con successo!");
        } else {
            alert(result.error || "Errore durante l'aggiunta del contenuto.");
        }
    } catch (error) {
        alert("Errore nella comunicazione con il server.");
    }
}

// Carica i contenuti all'avvio
loadExploreContent();
