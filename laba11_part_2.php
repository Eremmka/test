<?php
ob_start();
header('Content-Type: text/html; charset=utf-8');


ini_set('display_errors', 0);
error_reporting(E_ALL);

$results = [];
$subfolderResults = [];
$jpgList = [];

$dir1 = 'test';
if (!file_exists($dir1)) {
    if (mkdir($dir1)) {
        $results[] = ['status' => 'success', 'text' => "Папка '$dir1' создана"];
    } else {
        $results[] = ['status' => 'error', 'text' => "Ошибка создания папки '$dir1'"];
    }
} else {
    $results[] = ['status' => 'info', 'text' => "Папка '$dir1' уже существует"];
}

$newDir = 'www';
if (file_exists($dir1)) {
    if (rename($dir1, $newDir)) {
        $results[] = ['status' => 'success', 'text' => "Папка '$dir1' переименована в '$newDir'"];
    } else {
        $results[] = ['status' => 'error', 'text' => "Ошибка переименования папки"];
    }
} else {
    $results[] = ['status' => 'error', 'text' => "Папка '$dir1' не найдена"];
}

if (file_exists($newDir)) {
    if (@rmdir($newDir)) {
        $results[] = ['status' => 'success', 'text' => "Папка '$newDir' удалена"];
    } else {
        $results[] = ['status' => 'error', 'text' => "Ошибка удаления папки '$newDir' (возможно, не пуста)"];
    }
} else {
    $results[] = ['status' => 'error', 'text' => "Папка '$newDir' не существует"];
}

$dir1 = 'test';
if (!file_exists($dir1)) {
    mkdir($dir1);
    $results[] = ['status' => 'success', 'text' => "Папка '$dir1' создана заново"];
} else {
    $results[] = ['status' => 'info', 'text' => "Папка '$dir1' уже существует"];
}

$folders = ['images', 'css', 'js', 'uploads'];
foreach ($folders as $folderName) {
    $path = $dir1 . DIRECTORY_SEPARATOR . $folderName;
    if (!file_exists($path)) {
        if (mkdir($path)) {
            $subfolderResults[] = ['status' => 'success', 'name' => $path, 'text' => 'создана'];
        } else {
            $subfolderResults[] = ['status' => 'error', 'name' => $path, 'text' => 'ошибка создания'];
        }
    } else {
        $subfolderResults[] = ['status' => 'info', 'name' => $path, 'text' => 'уже существует'];
    }
}
$jpgList = glob("*.jpg");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Часть 2: Работа с папками</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
            background-color: #f8f9fa;
            color: #212529;
        }
        h1 {
            color: #0d6efd;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 8px;
        }
        .section {
            background: white;
            border-radius: 8px;
            padding: 15px 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .success { color: #198754; }
        .error { color: #dc3545; }
        .info { color: #0d6efd; }
        ul {
            list-style-type: none;
            padding-left: 5px;
        }
        li {
            padding: 5px 0;
            border-bottom: 1px dashed #eee;
        }
        li:last-child {
            border-bottom: none;
        }
        .badge {
            background-color: #e9ecef;
            padding: 3px 8px;
            border-radius: 20px;
            font-family: monospace;
            font-size: 0.9em;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <h1>Часть 2: Операции с папками</h1>
    <div class="section">
        <ul>
            <?php foreach ($results as $item): ?>
                <li class="<?= $item['status'] ?>">
                    <?= htmlspecialchars($item['text'], ENT_QUOTES, 'UTF-8') ?>
                </li>
            <?php endforeach; ?>

            
            <li class="info">
                Создание подпапок в 'test':
                <ul style="margin-left:30px;">
                    <?php foreach ($subfolderResults as $sub): ?>
                        <li class="<?= $sub['status'] ?>">
                            <?= htmlspecialchars($sub['name'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($sub['text'], ENT_QUOTES, 'UTF-8') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>

            
            <li class="info">
                 Файлы с расширением .jpg в текущей папке:
                <?php if (count($jpgList) > 0): ?>
                    <ul style="margin-left:30px;">
                        <?php foreach ($jpgList as $jpg): ?>
                            <li><span class="badge"><?= htmlspecialchars($jpg, ENT_QUOTES, 'UTF-8') ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <span style="margin-left:10px;">не найдены</span>
                <?php endif; ?>
            </li>
        </ul>
    </div>
</body>
</html>
