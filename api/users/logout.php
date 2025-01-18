<?php
/**
 * Endpoint per il logout degli utenti
 */

session_start(); // Avvia la sessione

// Verifica che l'utente sia autenticato
if (isset($_SESSION['user_id'])) {
    // Distrugge la sessione
    session_unset();
    session_destroy();

    // Risposta di successo
    echo json_encode(["message" => "Logout avvenuto con successo!"]);
} else {
    http_response_code(401); // Non autorizzato
    echo json_encode(["error" => "Utente non autenticato."]);
}
?>
