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
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Consultar la noticia
        $sql = "SELECT * FROM noticias WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $noticia = $stmt->fetch(PDO::FETCH_ASSOC);

        // Comprobar si la noticia fue encontrada
        if (!$noticia) {
            die("Noticia no encontrada.");
        }

        // Comprobar si se envía el formulario para actualizar
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $title = $_POST['title'];
            $content = $_POST['content'];
            $date = $_POST['date'];
            $template = $_POST['template'];

            // Actualizar la noticia
            $updateSql = "UPDATE noticias SET title = :title, content = :content, date = :date, template = :template WHERE id = :id";
            $updateStmt = $pdo->prepare($updateSql);
            $updateStmt->execute([
                'title' => $title,
                'content' => $content,
                'date' => $date,
                'template' => $template,
                'id' => $id
            ]);

            // Redireccionar a la página principal
            header('Location: index.html');
            exit();
        }
    } else {
        die("ID no especificado.");
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link rel="stylesheet" href="assets/css/styles.css"> <!-- Asegúrate de que esta ruta es correcta -->
    <script>
        // Función para mostrar/ocultar el campo de imagen
        function toggleImageUpload() {
            const templateSelect = document.getElementById('template');
            const imageUploadDiv = document.getElementById('image-upload');
            // Mostrar el campo de imagen solo si se selecciona la Plantilla 2
            if (templateSelect.value === 'template2') {
                imageUploadDiv.style.display = 'block';
            } else {
                imageUploadDiv.style.display = 'none';
            }
        }

        // Llamar a la función al cargar la página para establecer el estado inicial
        window.onload = function() {
            toggleImageUpload(); // Establece el estado inicial
            const templateSelect = document.getElementById('template');
            templateSelect.onchange = toggleImageUpload; // Cambia el evento onchange
        }
    </script>
</head>
<body class="dark-theme">
    <header class="header">
        <h1>Editar Noticia</h1>
    </header>

    <main class="container">
        <form id="edit-news-form" class="form" action="editar_noticia.php?id=<?php echo $noticia['id']; ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" id="news-id" name="id" value="<?php echo $noticia['id']; ?>">

            <label for="title" class="label">Título:</label>
            <input type="text" id="title" name="title" class="input" value="<?php echo htmlspecialchars($noticia['title']); ?>" required>

            <label for="content" class="label">Contenido:</label>
            <textarea id="content" name="content" class="textarea" rows="5" required><?php echo htmlspecialchars($noticia['content']); ?></textarea>

            <label for="date" class="label">Fecha:</label>
            <input type="date" id="date" name="date" class="input" value="<?php echo $noticia['date']; ?>" required>

            <label for="template" class="label">Elegir Plantilla:</label>
            <select id="template" name="template" class="select" required>
                <option value="template1" <?php if ($noticia['template'] == 'template1') echo 'selected'; ?>>Plantilla 1</option>
                <option value="template2" <?php if ($noticia['template'] == 'template2') echo 'selected'; ?>>Plantilla 2 (con imagen)</option>
            </select>

            <div id="image-upload" class="image-upload" style="display: none;"> <!-- Inicialmente oculto -->
                <label for="image" class="label">Subir imagen:</label>
                <input type="file" id="image" name="image" class="input" accept="image/*">
            </div>

            <button type="submit" class="button primary-button">Guardar Cambios</button>
            <button type="button" class="button danger-button" onclick="window.location.href='index.html'">Cancelar</button>
        </form>
    </main>
</body>
</html>
