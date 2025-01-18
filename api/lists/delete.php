<?php
/**
 * Endpoint per eliminare una lista
 */

require_once '../config/db.php';
session_start(); // Avvia la sessione

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Utente non autenticato."]);
    exit;
}

// Verifica che la richiesta sia di tipo DELETE
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id_lista = $data['id_lista'] ?? null;

    if (!$id_lista) {
        http_response_code(400);
        echo json_encode(["error" => "ID lista è obbligatorio!"]);
        exit;
    }

    try {
        $sql = "DELETE FROM user_lists 
                WHERE id = :id_lista AND user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id_lista', $id_lista);
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo json_encode(["message" => "Lista eliminata con successo!"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Lista non trovata."]);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Errore durante l'eliminazione della lista: " . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Metodo non consentito."]);
}
?>
