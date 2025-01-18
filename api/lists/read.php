<?php
/**
 * Endpoint per leggere le liste di un utente
 */

require_once '../config/db.php';
session_start(); // Avvia la sessione

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Utente non autenticato."]);
    exit;
}

// Recupera le liste dell'utente
try {
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT id, nome_lista, visibilita, created_at 
            FROM user_lists 
            WHERE user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $liste = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($liste);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Errore durante il recupero delle liste: " . $e->getMessage()]);
}
?>
