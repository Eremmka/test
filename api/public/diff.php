<?php
// diff.php
header('Content-Type: application/json; charset=utf-8');
$d1 = $_GET['date1'] ?? null;
$d2 = $_GET['date2'] ?? null;
if (!$d1 || !$d2 || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $d1) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $d2)) {
    http_response_code(400);
    echo json_encode(['error' => 'date1 и date2 обязательны (Y-m-d)']);
    exit;
}
$t1 = strtotime($d1);
$t2 = strtotime($d2);
if ($t1 === false || $t2 === false) {
    http_response_code(400);
    echo json_encode(['error' => 'Некорректная дата']);
    exit;
}
$days = abs($t2 - $t1) / 86400;
echo json_encode(['date1' => $d1, 'date2' => $d2, 'days_diff' => (int)$days]);
