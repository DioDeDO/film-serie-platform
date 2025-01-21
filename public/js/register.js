document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Impedisce l'invio predefinito del modulo

    // Raccogli i dati dai campi del modulo
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Seleziona l'elemento feedback per mostrare i messaggi
    const feedback = document.getElementById("feedback");

    try {
        // Invia la richiesta POST al backend
        const response = await fetch("../api/users/register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, email, password }), // Invia i dati come JSON
        });

        // Gestisci la risposta del server
        if (response.ok) {
            feedback.style.color = "green"; // Messaggio verde per il successo
            feedback.textContent = "Registrazione avvenuta con successo! Vai al login.";
            setTimeout(() => {
                window.location.href = "login.html"; // Reindirizza alla pagina di login
            }, 2000); // Ritardo di 2 secondi per mostrare il messaggio
        } else {
            // Mostra eventuali errori dal server
            const result = await response.json();
            feedback.style.color = "red"; // Messaggio rosso per gli errori
            feedback.textContent = result.error || "Errore durante la registrazione.";
        }
    } catch (error) {
        // Messaggio in caso di errore di comunicazione con il server
        feedback.style.color = "red";
        feedback.textContent = "Errore nella comunicazione con il server.";
        console.error("Errore nella richiesta:", error);
    }
});
