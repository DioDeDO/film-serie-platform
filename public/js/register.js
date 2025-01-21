// Aggiunge un evento al modulo per gestire la registrazione
document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Previene il comportamento predefinito del modulo (invio diretto)

    // Raccoglie i dati dai campi del modulo
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    // Elemento per il feedback visivo
    const feedback = document.getElementById("feedback");

    try {
        // Effettua la richiesta POST all'API di registrazione
        const response = await fetch("../api/users/register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, email, password }), // Invia i dati in formato JSON
        });

        // Elabora la risposta
        if (response.ok) {
            feedback.style.color = "green";
            feedback.textContent = "Registrazione avvenuta con successo! Vai al login.";
            setTimeout(() => {
                window.location.href = "login.html"; // Reindirizza al login dopo 2 secondi
            }, 2000);
        } else {
            const result = await response.json();
            feedback.style.color = "red";
            feedback.textContent = result.error || "Errore durante la registrazione.";
        }
    } catch (error) {
        // Gestisce gli errori di rete o comunicazione
        feedback.style.color = "red";
        feedback.textContent = "Errore nella comunicazione con il server.";
    }
});
