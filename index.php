<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias Actuales</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .news-card {
        margin-bottom: 20px;
    }

    .pagination {
        justify-content: center;
        margin: 20px 0;
    }

    #loading {
        margin-top: 20px;
        margin-bottom: 20px;
        color: #007bff;
        font-weight: bold;
    }
    </style>
</head>

<body>
    <div class="container mt-4">

        <h1 class="mb-4">Noticias Actuales</h1>

        <div id="loading" style="display:none; text-align:center;">
            <p>Cargando noticias...</p>
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div id="news-container"></div>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="scripts.js"></script>
</body>

</html>