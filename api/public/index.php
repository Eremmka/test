<?php
// index.php (CRUD) – полностью обновлённая версия
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config.php';

$action = $_GET['action'] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

try {
    // GET: получить все записи
    if ($method === 'GET' && $action === 'all') {
        $stmt = $pdo->query("SELECT id, title, content FROM records ORDER BY id");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC), JSON_UNESCAPED_UNICODE);
        exit;
    }

    // GET: получить одну запись по id
    if ($method === 'GET' && $action === 'get') {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'id обязателен и должен быть числом']);
            exit;
        }
        $stmt = $pdo->prepare("SELECT id, title, content FROM records WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rec) {
            echo json_encode($rec, JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Не найдено']);
        }
        exit;
    }

    // GET: удалить запись по id
    if ($method === 'GET' && $action === 'del') {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'id обязателен и должен быть числом']);
            exit;
        }
        $stmt = $pdo->prepare("DELETE FROM records WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Запись удалена']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Запись не найдена']);
        }
        exit;
    }

    // POST: создать новую запись (добавлено для полноты CRUD)
    if ($method === 'POST' && $action === 'add') {
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = $input['title'] ?? null;
        $content = $input['content'] ?? null;
        if ($title === null || $content === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Поля title и content обязательны']);
            exit;
        }
        $stmt = $pdo->prepare("INSERT INTO records (title, content) VALUES (:title, :content)");
        $stmt->execute([':title' => $title, ':content' => $content]);
        $newId = $pdo->lastInsertId();
        echo json_encode(['success' => true, 'id' => $newId, 'message' => 'Запись создана'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // POST: редактировать запись по id
    if ($method === 'POST' && $action === 'edit') {
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'id обязателен и должен быть числом']);
            exit;
        }
        $input = json_decode(file_get_contents('php://input'), true) ?: $_POST;
        $title = $input['title'] ?? null;
        $content = $input['content'] ?? null;
        if ($title === null || $content === null) {
            http_response_code(400);
            echo json_encode(['error' => 'Поля title и content обязательны']);
            exit;
        }
        // Проверка существования записи
        $stmt = $pdo->prepare("SELECT id FROM records WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        if (!$stmt->fetch()) {
            http_response_code(404);
            echo json_encode(['error' => 'Запись не найдена']);
            exit;
        }
        $stmt = $pdo->prepare("UPDATE records SET title = :title, content = :content WHERE id = :id");
        $stmt->execute([':title' => $title, ':content' => $content, ':id' => (int)$id]);
        echo json_encode(['success' => true, 'message' => 'Запись обновлена']);
        exit;
    }

    http_response_code(400);
    echo json_encode(['error' => 'Неизвестный action или метод']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка БД: ' . $e->getMessage()]);
}
