<?php
// Mostrar errores de PHP para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];
    $template = $_POST['template'];
    $imageBase64 = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imageFile = $_FILES['image']['tmp_name'];
        $imageData = file_get_contents($imageFile);
        $imageBase64 = base64_encode($imageData);
    }

    $sql = "INSERT INTO noticias (title, content, date, template, image) VALUES (:title, :content, :date, :template, :image)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':date', $date);
    $stmt->bindParam(':template', $template);
    $stmt->bindParam(':image', $imageBase64);

    if ($stmt->execute()) {
        $newsId = $pdo->lastInsertId();

        // 1. Generar el HTML de la noticia
        $htmlContent = "<!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>{$title}</title>
            <link rel='stylesheet' href='../assets/css/styles.css'>
        </head>
        <body class='dark-theme'>
            <header class='header'>
                <h1 class='news-title'>{$title}</h1>
            </header>
            <main class='container'>
                <article class='news-content'>
                    <p>{$content}</p>
                    <p><strong>Fecha:</strong> {$date}</p>";

        if ($template == 'template2' && !empty($imageBase64)) {
            $htmlContent .= "<img src='data:image/jpeg;base64,{$imageBase64}' alt='Imagen de la noticia' class='news-image' style='max-width: 300px; height: auto;'>";
        }

        $htmlContent .= "
                </article>
            </main>
            <footer class='footer'>
                <p>&copy; " . date("Y") . " Tu Instituto</p>
            </footer>
        </body>
        </html>";

        $htmlFilePath = "noticias/noticia_{$newsId}.html";
        file_put_contents($htmlFilePath, $htmlContent);

        // 2. Generar el QR con el enlace a la noticia
        $newsUrl = "http://localhost/CarteleraV3/Cartelera1/" . $htmlFilePath;  // Cambia esto si tu URL es diferente
        $qrCode = new QrCode($newsUrl);
        $writer = new PngWriter();
        $qrCodeFilePath = "noticias/qrcode_{$newsId}.png";
        $result = $writer->write($qrCode);
        $result->saveToFile($qrCodeFilePath);

        // 3. Guardar la ruta del archivo HTML y el QR en la base de datos
        $sqlUpdate = "UPDATE noticias SET html_file = :html_file, qr_code = :qr_code WHERE id = :id";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->bindParam(':html_file', $htmlFilePath);
        $stmtUpdate->bindParam(':qr_code', $qrCodeFilePath);
        $stmtUpdate->bindParam(':id', $newsId);
        $stmtUpdate->execute();

        echo json_encode(['success' => "Noticia guardada y QR generado exitosamente."]);
    } else {
        echo json_encode(['error' => "Error: No se pudo guardar la noticia."]);
    }
} else {
    echo json_encode(['error' => "Error: No se recibieron datos del formulario."]);
}
?>
