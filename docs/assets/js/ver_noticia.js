document.getElementById('news-form').addEventListener('submit', function(event) {
    event.preventDefault();

    // Obtener los datos del formulario
    const title = document.getElementById('title').value;
    const content = document.getElementById('content').value;
    const date = document.getElementById('date').value;
    const template = document.getElementById('template').value;
    const imageInput = document.getElementById('image').files[0];  // Obtener la imagen seleccionada

    if (imageInput) {
        // Si hay una imagen seleccionada, convertirla a base64
        const reader = new FileReader();
        reader.onloadend = function() {
            const imageBase64 = reader.result;  // Imagen en base64
            generateHTML(title, content, date, template, imageBase64);  // Llamamos a la función con la imagen
        };
        reader.readAsDataURL(imageInput);
    } else {
        // Si no hay imagen seleccionada, generar el HTML sin imagen
        generateHTML(title, content, date, template, '');
    }
});

function generateHTML(title, content, date, template, image) {
    // URL del logo del instituto (esto lo cambiarías por el logo real)
    const logoURL = 'https://upload.wikimedia.org/wikipedia/commons/a/a7/Logo_UNESCO.png';  


    // Crear el contenido HTML según la plantilla seleccionada
    let htmlContent = '';
    if (template === 'template1') {
        htmlContent = `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>${title}</title>
                <style>
                    body { font-family: 'Arial', sans-serif; margin: 40px; background-color: #f4f4f9; }
                    .header { text-align: center; }
                    .header img { max-width: 150px; }
                    .container { background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); }
                    h1 { color: #004080; }
                    p { font-size: 16px; color: #333; }
                    .footer { margin-top: 40px; text-align: center; font-size: 12px; color: #888; }
                    .date { margin-top: 20px; font-weight: bold; color: #555; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="${logoURL}" alt="Logo del Instituto">
                    <h1>${title}</h1>
                </div>
                <div class="container">
                    <p>${content}</p>
                    <p class="date">Fecha: ${date}</p>
                </div>
                <div class="footer">
                    <p>Instituto XYZ - Todos los derechos reservados</p>
                </div>
            </body>
            </html>
        `;
    } else if (template === 'template2') {
        htmlContent = `
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>${title}</title>
                <style>
                    body { font-family: 'Georgia', serif; margin: 40px; background-color: #eef2f7; }
                    .header { text-align: center; }
                    .header img { max-width: 150px; }
                    .container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); }
                    h1 { color: #006633; }
                    p { font-size: 18px; color: #444; }
                    img { max-width: 100%; margin-top: 20px; border-radius: 8px; }
                    .date { margin-top: 20px; font-weight: bold; color: #333; }
                    .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="${logoURL}" alt="Logo del Instituto">
                    <h1>${title}</h1>
                </div>
                <div class="container">
                    <p>${content}</p>
                    ${image ? `<img src="${image}" alt="Imagen relacionada">` : ''}
                    <p class="date">Fecha: ${date}</p>
                </div>
                <div class="footer">
                    <p>Instituto XYZ - Todos los derechos reservados</p>
                </div>
            </body>
            </html>
        `;
    }

    // Simular la generación de un archivo HTML accesible en línea
    const blob = new Blob([htmlContent], { type: 'text/html' });
    const url = URL.createObjectURL(blob);  // Creamos una URL temporal para el archivo HTML

    // Mostrar un enlace simulado (esto sería la URL del archivo en un servidor real)
    const link = document.createElement('a');
    link.href = url;
    link.textContent = "Haz clic aquí para ver la noticia";
    link.target = "_blank";  // Abrir en una nueva pestaña
    document.body.appendChild(link);  // Añadir el enlace al cuerpo del documento

    // Generar un código QR con la URL simulada
    generateQRCode(url);  // Usamos la URL simulada para el QR
}

// Función para generar código QR (asegúrate de tener QRCode.js cargado)
function generateQRCode(data) {
    let qrCodeContainer = document.getElementById('qr-code');
    qrCodeContainer.innerHTML = '';  // Limpiar cualquier código QR previo
    new QRCode(qrCodeContainer, {
        text: data,  // Contenido para el código QR (la URL del HTML generado)
        width: 128,  // Ancho del código QR
        height: 128  // Altura del código QR
    });
}
