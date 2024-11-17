<?php
// Configuración de conexión a la base de datos
$host = 'localhost';
$dbname = 'cartelera_instituto';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = $_POST['date'];
        $template = $_POST['template'];
        $imageBase64 = null;

        // Procesar la imagen si se cargó
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $imageData = file_get_contents($_FILES['image']['tmp_name']);
            $imageBase64 = base64_encode($imageData);
        }

        // Actualizar la noticia
        $sql = "UPDATE noticias SET title = ?, content = ?, date = ?, template = ?, image = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$title, $content, $date, $template, $imageBase64, $id]);

        echo "Noticia actualizada exitosamente.";
        header("Location: index.html"); // Redirigir a la página de índice
        exit();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
