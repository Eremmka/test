<?php
// day.php
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['day' => date('d')]);
