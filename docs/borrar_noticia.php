<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'cartelera_instituto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener el ID de la noticia
    $id = $_GET['id'];

    // Obtener las rutas de los archivos HTML y QR antes de eliminar la noticia
    $stmt = $pdo->prepare("SELECT html_file, qr_code FROM noticias WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $news = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($news) {
        // Eliminar los archivos si existen
        if (!empty($news['html_file']) && file_exists($news['html_file'])) {
            unlink($news['html_file']);
        }
        if (!empty($news['qr_code']) && file_exists($news['qr_code'])) {
            unlink($news['qr_code']);
        }

        // Borrar la noticia de la base de datos
        $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    // Redireccionar a la página principal
    header('Location: index.html');
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
