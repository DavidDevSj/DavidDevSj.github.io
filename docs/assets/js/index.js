// Función para cargar las noticias
function loadNews() {
    fetch("get_news.php") // Archivo PHP para obtener las noticias
        .then(response => response.json())
        .then(data => {
            const newsContainer = document.getElementById("news-container");
            newsContainer.innerHTML = ""; // Limpiar el contenedor

            data.forEach(news => {
                // Agrega este console.log para ver la imagen
                console.log("Imagen:", news.image); // Aquí se imprime la imagen

                // Crear un contenedor para cada noticia
                const newsItem = document.createElement("div");
                newsItem.classList.add("news-item"); // Clase para aplicar estilos

                // Añadir título
                const title = document.createElement("h2");
                title.textContent = news.title;
                newsItem.appendChild(title);

                // Añadir contenido
                const content = document.createElement("p");
                content.textContent = news.content;
                newsItem.appendChild(content);

                // Añadir fecha
                const date = document.createElement("p");
                date.textContent = `Fecha: ${news.date}`;
                newsItem.appendChild(date);

                // Mostrar imagen en miniatura si existe
                if (news.image) {
                    const img = document.createElement("img");
                    img.src = "data:image/jpeg;base64," + news.image; // Convertir la imagen desde base64
                    img.alt = news.title;
                    img.classList.add("thumbnail-image"); // Añadir clase para estilos
                    newsItem.appendChild(img);
                }

                // Añadir enlace al archivo HTML
                if (news.html_file) {
                    const link = document.createElement("a");
                    link.href = news.html_file;
                    link.textContent = "Ver Noticia Completa";
                    link.target = "_blank"; // Abrir en nueva pestaña
                    newsItem.appendChild(link);
                }

                // Añadir imagen del código QR si existe
                if (news.qr_code) {
                    const qrImage = document.createElement("img");
                    qrImage.src = news.qr_code; // Ruta del archivo QR
                    qrImage.alt = "Código QR";
                    qrImage.style.maxWidth = "150px"; // Limitar el tamaño del QR
                    newsItem.appendChild(qrImage);
                }

                // Crear botón de editar
                const editButton = document.createElement("button");
                editButton.textContent = "Editar";
                editButton.classList.add("button", "secondary-button");
                editButton.onclick = () => {
                    window.location.href = `editar_noticia.php?id=${news.id}`;
                };
                newsItem.appendChild(editButton);

                // Crear botón de eliminar
                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Eliminar";
                deleteButton.classList.add("button", "danger-button");
                deleteButton.onclick = () => {
                    if (confirm("¿Estás seguro de que quieres eliminar esta noticia?")) {
                        window.location.href = `borrar_noticia.php?id=${news.id}`;
                    }
                };
                newsItem.appendChild(deleteButton);

                // Añadir noticia al contenedor principal
                newsContainer.appendChild(newsItem);
            });
        })
        .catch(error => console.error("Error al cargar las noticias:", error));
}

// Cargar noticias al cargar la página
window.onload = loadNews;
