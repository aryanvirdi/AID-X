<?php
header('Content-Type: application/json');

define('DB_HOST', 'localhost');
define('DB_NAME', 'aidx_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Select relevant columns, only entries with non-null lat/lon
    $stmt = $pdo->query("SELECT fullname, phone, email, address, latitude, longitude, aidtype, details, type FROM aid_requests WHERE latitude IS NOT NULL AND longitude IS NOT NULL");

    $requests = $stmt->fetchAll();

    echo json_encode($requests);
} 
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
}
?>
