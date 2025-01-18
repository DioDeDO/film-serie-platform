<?php
/**
 * Endpoint per il login degli utenti
 */

// Inclusione della configurazione del database
require_once '../config/db.php';
session_start(); // Avvia una sessione PHP

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Legge i dati inviati dalla richiesta
    $data = json_decode(file_get_contents("php://input"), true);

    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    // Verifica che i dati siano stati inviati
    if (!$email || !$password) {
        http_response_code(400);
        echo json_encode(["error" => "Email e password sono obbligatorie!"]);
        exit;
    }

    try {
        // Cerca l'utente nel database
        $sql = "SELECT id, username, password FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se l'utente esiste e la password è corretta
        if ($user && password_verify($password, $user['password'])) {
            // Salva l'utente nella sessione
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            // Risposta di successo
            echo json_encode([
                "message" => "Login avvenuto con successo!",
                "username" => $user['username']
            ]);
        } else {
            http_response_code(401); // Non autorizzato
            echo json_encode(["error" => "Credenziali non valide!"]);
        }
    } catch (PDOException $e) {
        http_response_code(500); // Errore interno
        echo json_encode(["error" => "Errore durante il login: " . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Metodo non consentito
    echo json_encode(["error" => "Metodo non consentito."]);
}
?>
