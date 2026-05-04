<?php
// weekday.php
header('Content-Type: application/json; charset=utf-8');
$date = $_GET['date'] ?? null;
if (!$date || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Параметр date обязателен (Y-m-d)']);
    exit;
}
$ts = strtotime($date);
if ($ts === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Некорректная дата']);
    exit;
}
$days = ['Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота'];
echo json_encode(['date' => $date, 'weekday' => $days[(int)date('w',$ts)]]);
