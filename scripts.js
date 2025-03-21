$(document).ready(function() {
    let currentPage = 1;

    function loadNews(page) {
        // Mostrar loading
        $('#loading').show();
        $('#news-container').empty(); // Limpiar antes de cargar nuevas noticias

        $.ajax({
            url: 'api.php',
            method: 'GET',
            data: { page: page },
            success: function(response) {
                // Ocultar loading
                $('#loading').hide();

                // Mostrar artículos
                response.articles.forEach(function(article) {
                    let articleHtml = `
                        <div class="card news-card">
                            <div class="card-body">
                                <h5 class="card-title">${article.title}</h5>
                                <p class="card-text">${article.description}</p>
                                <p class="card-text">
                                    <small class="text-muted">
                                        Por: ${article.author} | ${article.publishedAt}
                                    </small>
                                </p>
                                <a href="${article.url}" class="btn btn-primary btn-sm" target="_blank">Leer más</a>
                            </div>
                        </div>
                    `;
                    $('#news-container').append(articleHtml);
                });

                // Generar paginación
                generatePagination(response.total_pages, response.current_page);
            },
            error: function() {
                $('#loading').hide(); // Ocultar loading si hay error
                $('#news-container').html('<p>Error al cargar las noticias</p>');
            }
        });
    }

    function generatePagination(totalPages, currentPage) {
        $('#pagination').empty();
        
        // Botón Anterior
        if (currentPage > 1) {
            $('#pagination').append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage - 1}">Anterior</a>
                </li>
            `);
        }

        // Números de página
        for (let i = 1; i <= totalPages; i++) {
            $('#pagination').append(`
                <li class="page-item ${i === currentPage ? 'active' : ''}">
                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                </li>
            `);
        }

        // Botón Siguiente
        if (currentPage < totalPages) {
            $('#pagination').append(`
                <li class="page-item">
                    <a class="page-link" href="#" data-page="${currentPage + 1}">Siguiente</a>
                </li>
            `);
        }
    }

    // Cargar primera página
    loadNews(currentPage);

    // Manejar clics en paginación
    $('#pagination').on('click', 'a', function(e) {
        e.preventDefault();
        currentPage = parseInt($(this).data('page'));
        loadNews(currentPage);
    });
});
