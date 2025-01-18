<?php
/**
 * Endpoint per fornire suggerimenti basati sulle liste dell'utente
 */

require_once '../config/db.php';
session_start(); // Avvia la sessione

// Verifica che l'utente sia autenticato
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Utente non autenticato."]);
    exit;
}

// Recupera i suggerimenti
try {
    $user_id = $_SESSION['user_id'];

    // Query per trovare generi più popolari nelle liste dell'utente
    $sql = "
        SELECT m.genere, COUNT(*) AS count
        FROM list_items li
        JOIN movies m ON li.movie_id = m.id
        WHERE li.list_id IN (SELECT id FROM user_lists WHERE user_id = :user_id)
        GROUP BY m.genere
        ORDER BY count DESC
        LIMIT 3
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    $popular_genres = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Recupera film/serie TV dello stesso genere
    $suggestions = [];
    foreach ($popular_genres as $genre) {
        $sql = "SELECT id, titolo, descrizione, genere, anno 
                FROM movies 
                WHERE genere = :genere 
                LIMIT 5";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':genere', $genre['genere']);
        $stmt->execute();
        $suggestions = array_merge($suggestions, $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Risultato
    echo json_encode($suggestions);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Errore durante il recupero dei suggerimenti: " . $e->getMessage()]);
}
?>
