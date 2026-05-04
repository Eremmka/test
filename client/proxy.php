<?php
// proxy.php – прокси для внешних API (обходит Mixed Content)
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$url = $_GET['url'] ?? null;
if (!$url) {
    http_response_code(400);
    echo json_encode(['error' => 'Параметр url обязателен']);
    exit;
}

// Разрешаем нужные API (добавили api.open-meteo.com)
$allowed_hosts = [
    'api.adviceslip.com',
    'catfact.ninja',
    'dog-api.kinduff.com',
    'api.open-meteo.com'
];
$host = parse_url($url, PHP_URL_HOST);
if (!in_array($host, $allowed_hosts)) {
    http_response_code(403);
    echo json_encode(['error' => 'Доступ к хосту ' . $host . ' запрещён']);
    exit;
}

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

if ($curl_error) {
    http_response_code(502);
    echo json_encode(['error' => 'Ошибка cURL: ' . $curl_error]);
    exit;
}
if ($http_code >= 200 && $http_code < 300) {
    echo $response;
} else {
    http_response_code(502);
    echo json_encode(['error' => "API вернул код {$http_code}"]);
}
