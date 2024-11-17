$(document).ready(function() {
    console.log("jQuery y Slick se han cargado correctamente.");
    $.ajax({
        url: 'get_noticias.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                console.error(data.error);
                alert(data.error);
                return;
            }

            let carouselContent = '';
            data.forEach(function(noticia) {
                carouselContent += `
                    <div class="carousel-item">
                        <h2>${noticia.title}</h2>
                        <img src="${noticia.qr_code}" alt="QR Code" style="max-width: 150px; height: auto;">
                        <a href="${noticia.html_file}" target="_blank">Ver noticia</a>
                    </div>
                `;
            });

            $('#news-carousel').html(carouselContent);

            // Inicializar el carrusel
            $('#news-carousel').slick({
                autoplay: true,
                autoplaySpeed: 2500, // Cambia de imagen cada 3 segundos
                speed: 800, // Duración de la transición en milisegundos (0.5 segundos)
                cssEase: 'ease-in-out', // Transición suave
                dots: true,
                arrows: false, // Desactiva los botones de navegación
                slidesToShow: 1,
                slidesToScroll: 1,
            });
            
            
            
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
            alert('Error al cargar las noticias. Intenta de nuevo.');
        }
    });
});
