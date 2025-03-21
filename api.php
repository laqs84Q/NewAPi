<?php
header('Content-Type: application/json');

// Configuración
$news_api_key = '4f20f458f2f24f5594e8e465803bd0c8';
$articles_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;


function httpGet($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'User-Agent: MiAplicacionNoticias/1.0',
        'Accept: application/json'
    ]);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        $error = curl_error($ch);
        curl_close($ch);
        return ['error' => true, 'message' => "Error en cURL: $error"];
    }

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code !== 200) {
        return [
            'error' => true,
            'message' => "Error HTTP $http_code",
            'details' => json_decode($response, true) ?? $response
        ];
    }

    return json_decode($response, true);
}

// Obtener autores aleatorios
$authors_response = httpGet('https://randomuser.me/api/?nat=es&results=10');

if (isset($authors_response['error'])) {
    die(json_encode(['error' => true, 'message' => $authors_response['message']]));
}

$authors = $authors_response['results'] ?? [];

// Obtener noticias
$news_url = "https://newsapi.org/v2/top-headlines?country=us&apiKey=$news_api_key&pageSize=100";
$news_response = httpGet($news_url);

if (isset($news_response['error'])) {
    die(json_encode(['error' => true, 'message' => $news_response['message'], 'details' => $news_response['details'] ?? 'Sin detalles adicionales']));
}

$articles = $news_response['articles'] ?? [];
$total_articles = count($articles);
$start = ($page - 1) * $articles_per_page;
$articles_to_show = array_slice($articles, $start, $articles_per_page);

// Preparar respuesta
$response = [
    'articles' => [],
    'total_pages' => ceil($total_articles / $articles_per_page),
    'current_page' => $page
];

foreach ($articles_to_show as $index => $article) {
    $author = $authors[$index % count($authors)];
    $response['articles'][] = [
        'title' => htmlspecialchars($article['title'] ?? 'Sin título'),
        'description' => htmlspecialchars($article['description'] ?? 'Sin descripción'),
        'url' => htmlspecialchars($article['url'] ?? '#'),
        'publishedAt' => date('d/m/Y', strtotime($article['publishedAt'] ?? 'now')),
        'author' => htmlspecialchars($author['name']['first'] . ' ' . $author['name']['last'])
    ];
}

echo json_encode($response);
