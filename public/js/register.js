document.getElementById("registerForm").addEventListener("submit", async function (e) {
    e.preventDefault(); // Impedisce l'invio predefinito del modulo (evita GET con i dati nell'URL)

    // Raccogli i dati dai campi del modulo
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;

    const feedback = document.getElementById("feedback");

    try {
        // Invia la richiesta POST al backend
        const response = await fetch("../api/users/register.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ username, email, password }), // Invia i dati come JSON
        });

        if (response.ok) {
            feedback.style.color = "green";
            feedback.textContent = "Registrazione avvenuta con successo! Vai al login.";
            setTimeout(() => {
                window.location.href = "login.html"; // Reindirizza alla pagina di login
            }, 2000);
        } else {
            const result = await response.json();
            feedback.style.color = "red";
            feedback.textContent = result.error || "Errore durante la registrazione.";
        }
    } catch (error) {
        feedback.style.color = "red";
        feedback.textContent = "Errore nella comunicazione con il server.";
    }
});
