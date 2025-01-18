<?php
/**
 * Endpoint per creare una nuova lista
 */

require_once '../config/db.php';
session_start(); // Avvia la sessione

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Utente non autenticato."]);
    exit;
}

// Verifica che la richiesta sia di tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $nome_lista = $data['nome_lista'] ?? null;
    $visibilita = $data['visibilita'] ?? 'privata';

    if (!$nome_lista) {
        http_response_code(400);
        echo json_encode(["error" => "Il nome della lista è obbligatorio!"]);
        exit;
    }

    try {
        // Inserimento della lista nel database
        $sql = "INSERT INTO user_lists (user_id, nome_lista, visibilita) VALUES (:user_id, :nome_lista, :visibilita)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':nome_lista', $nome_lista);
        $stmt->bindParam(':visibilita', $visibilita);
        $stmt->execute();

        echo json_encode(["message" => "Lista creata con successo!"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Errore durante la creazione della lista: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito."]);
}
?>
