<?php
/**
 * Endpoint per aggiungere un contenuto a una lista
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

    $list_id = $data['list_id'] ?? null;
    $movie_id = $data['movie_id'] ?? null;

    if (!$list_id || !$movie_id) {
        http_response_code(400);
        echo json_encode(["error" => "ID lista e ID contenuto sono obbligatori!"]);
        exit;
    }

    try {
        $sql = "INSERT INTO list_items (list_id, movie_id) VALUES (:list_id, :movie_id)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':list_id', $list_id);
        $stmt->bindParam(':movie_id', $movie_id);
        $stmt->execute();

        echo json_encode(["message" => "Contenuto aggiunto con successo alla lista!"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Errore durante l'aggiunta del contenuto: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito."]);
}
?>
