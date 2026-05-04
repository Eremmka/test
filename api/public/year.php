<?php
// year.php
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['year' => date('Y')]);
