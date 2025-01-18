<?php
/**
 * Endpoint per la registrazione degli utenti
 */

// Inclusione della configurazione del database
require_once '../config/db.php';

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Legge i dati dalla richiesta
    $data = json_decode(file_get_contents("php://input"), true);

    // Controllo dei dati inviati
    $username = $data['username'] ?? null;
    $email = $data['email'] ?? null;
    $password = $data['password'] ?? null;

    if (!$username || !$email || !$password) {
        http_response_code(400);
        echo json_encode(["error" => "Tutti i campi sono obbligatori!"]);
        exit;
    }

    // Hash della password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    try {
        // Inserimento dell'utente nel database
        $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        // Risposta in caso di successo
        http_response_code(201);
        echo json_encode(["message" => "Registrazione avvenuta con successo!"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Errore durante la registrazione: " . $e->getMessage()]);
    }
} else {
    http_response_code(405); // Metodo non consentito
    echo json_encode(["error" => "Metodo non consentito."]);
}
?>
