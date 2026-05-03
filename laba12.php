<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа 12</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f7f7f7;
            color: #333;
        }
        h3 {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        .error {
            color: #c00;
            font-weight: bold;
        }
        .info {
            color: #036;
        }
        form {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            display: inline-block;
        }
        input[type="text"] {
            padding: 5px;
            width: 200px;
        }
        input[type="submit"] {
            padding: 5px 15px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<?php
echo "<h3>Часть 1. Обработка исключений</h3>";

try {
    $filename = 'nonexistent_file.txt';
    $handle = @fopen($filename, 'r');
    if (!$handle) {
        throw new Exception("Не удалось открыть файл '{$filename}'");
    }
    fclose($handle);
} catch (Exception $ex) {
    echo "<span class='error'>Исключение (fopen): " . $ex->getMessage() . "</span><br>";
}

function divide($a, $b) {
    if ($b == 0) {
        throw new Exception("Деление на ноль: {$a} / {$b}");
    }
    return $a / $b;
}

try {
    $result = divide(10, 0);
    echo "Результат: {$result}<br>";
} catch (Exception $ex) {
    $errorMsg = "[" . date('Y-m-d H:i:s') . "] " . $ex->getMessage() . "\n";
    file_put_contents('log.txt', $errorMsg, FILE_APPEND);
    echo "<span class='error'>Исключение (деление): сообщение записано в log.txt</span><br>";
}

function getArrayElement($array, $key) {
    if (!array_key_exists($key, $array)) {
        throw new Exception("Ключ '{$key}' отсутствует в массиве");
    }
    return $array[$key];
}

$countries = ['Spain' => 'Madrid', 'Russia' => 'Moscow'];

try {
    $capital = getArrayElement($countries, 'Germany');
    echo "Столица Германии: {$capital}<br>";
} catch (Exception $ex) {
    echo "<span class='error'>Исключение (массив): " . $ex->getMessage() . "</span><br>";
}

echo "<hr>";

echo "<h3>Часть 2. Работа с датами</h3>";

$timestamp1 = mktime(10, 25, 0, 3, 15, 2025);
echo "<span class='info'>1. Timestamp 15.03.2025 10:25:00: {$timestamp1}</span><br>";

$past = mktime(8, 5, 59, 10, 2, 1990);
$now = time();
$diffSeconds = $now - $past;
echo "<span class='info'>2. Разница в секундах (с 02.10.1990 08:05:59 до сейчас): {$diffSeconds}</span><br>";

echo "<span class='info'>3. Текущие дата и время: " . date('Y.m.d H:i:s') . "</span><br>";

$firstSep = mktime(0, 0, 0, 9, 1);
echo "<span class='info'>4. 1 сентября текущего года: " . date('Y.m.d', $firstSep) . "</span><br>";

$dayWord = date('l', mktime(0, 0, 0, 2, 2, 2000));
echo "<span class='info'>5. 2 февраля 2000 года был день недели: {$dayWord}</span><br>";

$week = [
    'Воскресенье',
    'Понедельник',
    'Вторник',
    'Среда',
    'Четверг',
    'Пятница',
    'Суббота'
];
$todayIndex = date('w');
echo "<span class='info'>6. Сегодня: {$week[$todayIndex]}</span><br>";

$birthdayIndex = date('w', mktime(0, 0, 0, 6, 12, 2016));
echo "<span class='info'>   12 июня 2016 года был день недели: {$week[$birthdayIndex]}</span><br>";

echo "<h4>7. Сравнение двух дат</h4>";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['date1'], $_POST['date2'])) {
    $date1 = $_POST['date1'];
    $date2 = $_POST['date2'];
    $ts1 = strtotime($date1);
    $ts2 = strtotime($date2);
    if ($ts1 === false || $ts2 === false) {
        echo "<span class='error'>Ошибка: неверный формат даты. Используйте ГГГГ-ММ-ДД.</span><br>";
    } else {
        echo "<span class='info'>Первая дата: {$date1}, вторая дата: {$date2}</span><br>";
        if ($ts1 > $ts2) {
            echo "<span class='info'>Большая дата: {$date1}</span><br>";
        } elseif ($ts2 > $ts1) {
            echo "<span class='info'>Большая дата: {$date2}</span><br>";
        } else {
            echo "<span class='info'>Даты равны.</span><br>";
        }
    }
    echo "<hr>";
}
?>

<form method="post">
    Введите первую дату (ГГГГ-ММ-ДД): <input type="text" name="date1" placeholder="2025-12-31"><br><br>
    Введите вторую дату (ГГГГ-ММ-ДД): <input type="text" name="date2" placeholder="2025-12-31"><br><br>
    <input type="submit" value="Сравнить">
</form>

<?php
$dateYMD = '2025-03-15';
$converted = date('d-m-Y', strtotime($dateYMD));
echo "<br><span class='info'>8. Дата '{$dateYMD}' в формате день-месяц-год: {$converted}</span><br>";

$date = date_create('2000-02-03');
date_modify($date, '+2 days');
date_modify($date, '+1 month');
date_modify($date, '+3 days');
date_modify($date, '+1 year');
echo "<span class='info'>9. Дата после прибавлений: " . date_format($date, 'Y-m-d') . "</span><br>";
date_modify($date, '-3 days');
echo "<span class='info'>   После вычитания 3 дней: " . date_format($date, 'Y-m-d') . "</span><br>";

$currentYear = date('Y');
$nextNewYear = mktime(0, 0, 0, 1, 1, $currentYear + 1);
$secondsLeft = $nextNewYear - time();
$daysLeft = floor($secondsLeft / 86400);
echo "<span class='info'>10. Дней до Нового года: {$daysLeft}</span><br>";
?>

</body>
</html>
