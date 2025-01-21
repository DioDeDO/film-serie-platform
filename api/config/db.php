<?php
/**
 * Configurazione della connessione al database
 */

// Parametri di connessione
$host = 'localhost'; // Nome host
$db_name = 'film_series_db'; // Nome del database
$username = 'root'; // Nome utente del database
$password = ''; // Password del database (lascia vuoto se usi XAMPP di default)

try {
    // Creazione di una connessione PDO
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Test temporaneo
    die("Connessione riuscita!"); // Rimuovi questa riga dopo il test
} catch (PDOException $e) {
    // Gestione degli errori di connessione
    die("Errore di connessione: " . $e->getMessage());
}
?>
