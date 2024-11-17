document.getElementById("news-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Evitar que el formulario se envíe de forma tradicional

    var formData = new FormData(this);

    fetch("guardar_noticia.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log(data.success);
            alert(data.success); // Opcional, para mostrar una alerta
            window.location.href = "index.html"; // Redirigir a la página principal
        } else {
            console.error(data.error);
            alert(data.error); // Opcional, para mostrar una alerta
        }
    })
    .catch(error => {
        console.error("Error:", error);
    });
});

// Función para mostrar/ocultar el campo de imagen según la plantilla seleccionada
const templateSelect = document.getElementById("template");
const imageUploadDiv = document.getElementById("image-upload");

// Inicializar la visibilidad del campo de imagen
function updateImageUploadVisibility() {
    if (templateSelect.value === "template2") {
        imageUploadDiv.style.display = "block"; // Mostrar campo de imagen
    } else {
        imageUploadDiv.style.display = "none"; // Ocultar campo de imagen
    }
}

// Agregar un evento de cambio al select de templates
templateSelect.addEventListener("change", updateImageUploadVisibility);

// Llamar a la función para establecer el estado inicial
updateImageUploadVisibility();
