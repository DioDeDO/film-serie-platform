<?php
/**
 * Endpoint per recuperare contenuti disponibili
 */

require_once '../config/db.php';

// Recupera un elenco di contenuti
try {
    $sql = "SELECT id, titolo, descrizione, genere, anno FROM movies LIMIT 20";
    $stmt = $pdo->query($sql);

    $content = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($content);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Errore durante il recupero dei contenuti: " . $e->getMessage()]);
}
?>
