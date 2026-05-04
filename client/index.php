<?php
// client/index.php – полностью на PHP, тестирует API через кнопки и формы без JavaScript

$base = 'http://kaleever.com/api/public/';

// Функция для вызова API (GET)
function apiGet($path) {
    global $base;
    $url = $base . $path;
    $resp = @file_get_contents($url);
    if ($resp === false) return ['error' => 'Ошибка запроса'];
    return json_decode($resp, true) ?: ['error' => 'Неверный JSON'];
}

// Функция для POST-запроса к API
function apiPost($path, $data) {
    global $base;
    $url = $base . $path;
    $opts = [
        'http' => [
            'method'  => 'POST',
            'header'  => 'Content-Type: application/json',
            'content' => json_encode($data)
        ]
    ];
    $context = stream_context_create($opts);
    $resp = @file_get_contents($url, false, $context);
    return json_decode($resp, true) ?: ['error' => 'Ошибка запроса'];
}

// Функция для получения погоды (curl)
function getWeather() {
    $url = 'https://api.open-meteo.com/v1/forecast?latitude=59.9386&longitude=30.2141&current_weather=true';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    curl_close($ch);
    return json_decode($resp, true) ?: ['error' => 'Ошибка Open-Meteo'];
}

// ======= Обработка действий из параметров URL =======
$dayResult = $monthResult = $yearResult = null;
$weekdayResult = $diffResult = $citiesResult = null;
$weatherResult = null;
$crudMessage = '';
$records = [];
$editData = null;

if (isset($_GET['show'])) {
    $show = $_GET['show'];
    if ($show === 'day') $dayResult = apiGet('day.php');
    elseif ($show === 'month') $monthResult = apiGet('month.php');
    elseif ($show === 'year') $yearResult = apiGet('year.php');
}

if (isset($_GET['test_weekday']) && isset($_GET['date1'])) {
    $weekdayResult = apiGet('weekday.php?date=' . urlencode($_GET['date1']));
}

if (isset($_GET['test_diff']) && isset($_GET['diff_date1']) && isset($_GET['diff_date2'])) {
    $diffResult = apiGet('diff.php?date1=' . urlencode($_GET['diff_date1']) . '&date2=' . urlencode($_GET['diff_date2']));
}

if (isset($_GET['test_cities']) && isset($_GET['country'])) {
    $citiesResult = apiGet('cities.php?country=' . urlencode($_GET['country']));
}

if (isset($_GET['test_weather'])) {
    $weatherResult = getWeather();
}

// CRUD действия
if (isset($_GET['del'])) {
    $delId = (int)$_GET['del'];
    $res = apiGet('index.php?action=del&id=' . $delId);
    $crudMessage = isset($res['success']) ? 'Запись удалена' : ('Ошибка: ' . ($res['error'] ?? 'неизвестно'));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['crud_action'])) {
    if ($_POST['crud_action'] === 'add') {
        $res = apiPost('index.php?action=add', ['title' => $_POST['title'], 'content' => $_POST['content']]);
        $crudMessage = isset($res['success']) ? 'Запись создана' : ('Ошибка: ' . ($res['error'] ?? 'неизвестно'));
    } elseif ($_POST['crud_action'] === 'edit') {
        $id = (int)$_POST['id'];
        $res = apiPost('index.php?action=edit&id=' . $id, ['title' => $_POST['title'], 'content' => $_POST['content']]);
        $crudMessage = isset($res['success']) ? 'Запись обновлена' : ('Ошибка: ' . ($res['error'] ?? 'неизвестно'));
    }
}

// Режим редактирования
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $rec = apiGet('index.php?action=get&id=' . $editId);
    if (isset($rec['id'])) {
        $editData = $rec;
    } else {
        $crudMessage = 'Запись не найдена';
    }
}

// Получаем все записи для таблицы
$records = apiGet('index.php?action=all');
if (!is_array($records)) $records = [];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Тестирование API (PHP)</title>
    <style>
        body { font-family: Arial; max-width: 900px; margin: 30px auto; padding: 0 15px; }
        .section { border: 1px solid #ccc; border-radius: 8px; padding: 15px; margin-bottom: 25px; }
        button, .btn { padding: 8px 16px; cursor: pointer; margin-right: 5px; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 4px; overflow-x: auto; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        input, textarea { width: 100%; padding: 5px; box-sizing: border-box; margin-bottom: 5px; }
        .message { font-weight: bold; margin: 10px 0; }
    </style>
</head>
<body>
<h1>Лабораторная 19 – Тестирование API (чистый PHP)</h1>

<!-- День, месяц, год -->
<div class="section">
    <h2>Дата</h2>
    <a href="?show=day" class="btn">День</a>
    <a href="?show=month" class="btn">Месяц</a>
    <a href="?show=year" class="btn">Год</a>
    <?php if ($dayResult): ?><pre>День: <?php echo htmlspecialchars($dayResult['day'] ?? 'ошибка'); ?></pre><?php endif; ?>
    <?php if ($monthResult): ?><pre>Месяц: <?php echo htmlspecialchars($monthResult['month'] ?? 'ошибка'); ?></pre><?php endif; ?>
    <?php if ($yearResult): ?><pre>Год: <?php echo htmlspecialchars($yearResult['year'] ?? 'ошибка'); ?></pre><?php endif; ?>
</div>

<!-- День недели -->
<div class="section">
    <h2>День недели по дате</h2>
    <form method="get">
        <input type="date" name="date1" value="<?php echo htmlspecialchars($_GET['date1'] ?? '2025-12-25'); ?>">
        <button type="submit" name="test_weekday" value="1">Узнать</button>
    </form>
    <?php if ($weekdayResult): ?><pre><?php echo htmlspecialchars(print_r($weekdayResult, true)); ?></pre><?php endif; ?>
</div>

<!-- Разница дат -->
<div class="section">
    <h2>Разница между датами</h2>
    <form method="get">
        <input type="date" name="diff_date1" value="<?php echo htmlspecialchars($_GET['diff_date1'] ?? '2025-01-01'); ?>">
        <input type="date" name="diff_date2" value="<?php echo htmlspecialchars($_GET['diff_date2'] ?? '2025-12-31'); ?>">
        <button type="submit" name="test_diff" value="1">Посчитать</button>
    </form>
    <?php if ($diffResult): ?><pre><?php echo htmlspecialchars(print_r($diffResult, true)); ?></pre><?php endif; ?>
</div>

<!-- Города -->
<div class="section">
    <h2>Города по стране</h2>
    <form method="get">
        <input type="text" name="country" value="<?php echo htmlspecialchars($_GET['country'] ?? 'Россия'); ?>" placeholder="Россия">
        <button type="submit" name="test_cities" value="1">Получить</button>
    </form>
    <?php if ($citiesResult): ?><pre><?php echo htmlspecialchars(print_r($citiesResult, true)); ?></pre><?php endif; ?>
</div>

<!-- Погода -->
<div class="section">
    <h2>Погода (Open-Meteo, СПб)</h2>
    <a href="?test_weather=1" class="btn">Текущая погода</a>
    <?php if ($weatherResult): ?><pre><?php echo htmlspecialchars(print_r($weatherResult, true)); ?></pre><?php endif; ?>
</div>

<!-- CRUD -->
<div class="section">
    <h2>Управление записями (CRUD)</h2>
    <?php if ($crudMessage): ?><div class="message"><?php echo htmlspecialchars($crudMessage); ?></div><?php endif; ?>

    <!-- Форма добавления/редактирования -->
    <form method="post">
        <input type="hidden" name="crud_action" value="<?php echo $editData ? 'edit' : 'add'; ?>">
        <?php if ($editData): ?><input type="hidden" name="id" value="<?php echo $editData['id']; endif; ?>">
        <label>Заголовок:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($editData['title'] ?? ''); ?>" required>
        <label>Содержание:</label>
        <textarea name="content" rows="3" required><?php echo htmlspecialchars($editData['content'] ?? ''); ?></textarea>
        <button type="submit"><?php echo $editData ? 'Сохранить изменения' : 'Создать запись'; ?></button>
        <?php if ($editData): ?>
            <a href="index.php" class="btn">Отмена</a>
        <?php endif; ?>
    </form>

    <h3>Существующие записи</h3>
    <table>
        <tr><th>ID</th><th>Заголовок</th><th>Действия</th></tr>
        <?php foreach ($records as $rec): ?>
        <tr>
            <td><?php echo $rec['id']; ?></td>
            <td><?php echo htmlspecialchars($rec['title']); ?></td>
            <td>
                <a href="?edit=<?php echo $rec['id']; ?>" class="btn">Изменить</a>
                <a href="?del=<?php echo $rec['id']; ?>" class="btn" onclick="return confirm('Удалить?');">Удалить</a>
            </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($records)): ?><tr><td colspan="3">Нет записей</td></tr><?php endif; ?>
    </table>
</div>
</body>
</html>
