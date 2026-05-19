<?php
define('DB_HOST', 'db');
define('DB_USER', 'appuser');
define('DB_PASS', 'apppass');
define('DB_NAME', 'cadastro');

function getConnection(): PDO {
    try {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die(json_encode(['erro' => 'Falha na conexão: ' . $e->getMessage()]));
    }
}
