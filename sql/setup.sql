CREATE DATABASE IF NOT EXISTS film_series_db;
USE film_series_db;

-- Tabella utenti
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabella liste
CREATE TABLE IF NOT EXISTS user_lists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nome_lista VARCHAR(100),
    visibilita ENUM('pubblica', 'privata') DEFAULT 'privata',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Tabella film/serie in lista
CREATE TABLE IF NOT EXISTS list_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    list_id INT NOT NULL,
    movie_id INT NOT NULL,
    commento TEXT,
    valutazione INT CHECK (valutazione BETWEEN 1 AND 10),
    FOREIGN KEY (list_id) REFERENCES user_lists(id) ON DELETE CASCADE
);

-- Tabella film
CREATE TABLE IF NOT EXISTS movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titolo VARCHAR(255),
    descrizione TEXT,
    genere VARCHAR(50),
    anno INT
);
