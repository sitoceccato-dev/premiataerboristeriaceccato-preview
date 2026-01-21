<?php
/**
 * Configurazione Database
 * Modifica questi valori con i tuoi dati
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'erboristeria_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Connessione
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("Errore connessione database: " . $e->getMessage());
}

// Info sito (modificabili)
$sito = [
    'nome' => 'Premiata Erboristeria Ceccato',
    'titolare' => '[Nome Cognome]',
    'indirizzo' => 'Via Example 123',
    'citta' => '38050 Levico Terme (TN) - Italia',
    'telefono' => '+39 0461 123456',
    'email' => 'info@erboristeria-ceccato.it',
    'piva' => '01234567890',
    'spedizione_gratis' => 49
];
