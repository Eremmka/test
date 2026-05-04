<?php
// cities.php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';
$country = $_GET['country'] ?? null;
if (!$country) {
    http_response_code(400);
    echo json_encode(['error' => 'Параметр country обязателен']);
    exit;
}
try {
    $stmt = $pdo->prepare("SELECT c.name FROM cities c JOIN countries co ON c.country_id = co.id WHERE co.name = :country ORDER BY c.name");
    $stmt->execute([':country' => $country]);
    $cities = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    echo json_encode(['country' => $country, 'cities' => $cities ?: []], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка БД']);
}
