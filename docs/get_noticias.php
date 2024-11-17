<?php
header('Content-Type: application/json');

$host = 'localhost';
$dbname = 'cartelera_instituto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $stmt = $pdo->query("SELECT title, qr_code, html_file FROM noticias");
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Comentar esta línea para ver el resultado en el navegador
    // echo json_encode($noticias);
    
    if (!$noticias) {
        echo json_encode(['error' => 'No se encontraron noticias.']);
    } else {
        echo json_encode($noticias);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => "Error en la conexión: " . $e->getMessage()]);
}
?>
