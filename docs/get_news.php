<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'cartelera_instituto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Consultar las noticias y asegurarse de incluir html_file y qr_code
    $sql = "SELECT id, title, content, date, image, html_file, qr_code FROM noticias";
    $stmt = $pdo->query($sql);
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir las imágenes de blob a base64
    foreach ($noticias as &$noticia) {
        if ($noticia['image'] !== null) {
            $noticia['image'] = base64_encode($noticia['image']);
        }
    }

    // Devolver las noticias en formato JSON
    echo json_encode($noticias);
} catch (PDOException $e) {
    echo json_encode(['error' => "Error en la conexión: " . $e->getMessage()]);
}
