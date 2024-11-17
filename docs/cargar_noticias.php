<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'cartelera_instituto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
} catch (PDOException $e) {
    echo json_encode(['error' => "Error en la conexión: " . $e->getMessage()]);
    exit;
}

// Consultar todas las noticias
$sql = "SELECT id, title, date, html_file, qr_code FROM noticias";
$stmt = $pdo->query($sql);
$noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Devolver las noticias en formato JSON
echo json_encode($noticias);
?>
