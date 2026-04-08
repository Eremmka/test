<?php
$baseDir = __DIR__ . '/';

if (!is_writable($baseDir)) {
    $permError = true;
} else {
    $permError = false;
}

$messages = [];

function addMessage($text, $type = 'success') {
    global $messages;
    $messages[] = ['text' => $text, 'type' => $type];
}

if (!$permError) {
    $testFile = $baseDir . 'test.txt';
    $content = 'Привет, мир!';
    if (file_put_contents($testFile, $content)) {
        addMessage('Файл test.txt создан и записан.', 'success');
    } else {
        addMessage('Не удалось создать test.txt.', 'error');
    }

    if (file_exists($testFile)) {
        $readContent = file_get_contents($testFile);
        addMessage("Содержимое test.txt: <strong>" . htmlspecialchars($readContent) . "</strong>", 'info');
    } else {
        addMessage('Файл test.txt не найден.', 'error');
    }

    $mirFile = $baseDir . 'mir.txt';
    if (rename($testFile, $mirFile)) {
        addMessage('Файл test.txt переименован в mir.txt.', 'success');
    } else {
        addMessage('Не удалось переименовать test.txt.', 'error');
    }

    $folder = $baseDir . 'folder';
    if (!file_exists($folder)) {
        if (mkdir($folder)) {
            addMessage('Папка folder создана.', 'success');
        } else {
            addMessage('Не удалось создать папку folder.', 'error');
        }
    } else {
        addMessage('Папка folder уже существует.', 'info');
    }

    $mirPath = $folder . '/' . basename($mirFile);
    if (rename($mirFile, $mirPath)) {
        addMessage('Файл mir.txt перемещён в папку folder.', 'success');
    } else {
        addMessage('Не удалось переместить mir.txt.', 'error');
    }

    $worldPath = $folder . '/world.txt';
    if (copy($mirPath, $worldPath)) {
        addMessage('Создана копия world.txt в папке folder.', 'success');
    } else {
        addMessage('Не удалось скопировать файл.', 'error');
    }

    if (file_exists($worldPath)) {
        $sizeBytes = filesize($worldPath);
        $sizeKB = $sizeBytes / 1024;
        $sizeMB = $sizeKB / 1024;
        $sizeGB = $sizeMB / 1024;
        addMessage(
            "Размер файла world.txt:

            Байты: $sizeBytes

            Килобайты: " . round($sizeKB, 2) . "

            Мегабайты: " . round($sizeMB, 6) . "

            Гигабайты: " . round($sizeGB, 12),
            'info'
        );
    } else {
        addMessage('Файл world.txt не найден.', 'error');
    }

    if (unlink($worldPath)) {
        addMessage('Файл world.txt удалён.', 'success');
    } else {
        addMessage('Не удалось удалить world.txt.', 'error');
    }

    $worldExists = file_exists($worldPath) ? 'существует' : 'не существует';
    $mirExists = file_exists($mirPath) ? 'существует' : 'не существует';
    addMessage("Проверка существования:
world.txt: $worldExists
mir.txt: $mirExists", 'info');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа 11 - Работа с файлами</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .result-container {
            background: white;
	    padding: 30px;
	    border-radius: 10px;
	    width: 60%;
	    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
 	    margin: 0 auto 20px;
        }
        .result-container h2 {
            text-align: center;
            color: #333;
            margin-bottom: 25px;
            font-size: 28px;
        }
        .message {
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            font-size: 15px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        .message.info {
            background: #d1ecf1;


color: #0c5460;
            border-left: 4px solid #17a2b8;
        }
       }
    </style>
</head>

<body>
    <div class="container">
        <div class="result-container">
            <h2>Лабораторная работа 11: Работа с файлами</h2>
            <?php if ($permError): ?>
                <div class="error-block">
                    <strong>Ошибка прав доступа!</strong>

                    Нет прав на запись в текущую директорию.

                    Выполните команду в терминале: <code>sudo chmod 777 <?= $baseDir ?></code>

                    После выполнения работы рекомендуется вернуть права: <code>sudo chmod 755 <?= $baseDir ?></code>
                </div>
            <?php else: ?>
                <?php foreach ($messages as $msg): ?>
                    <div class="message <?= htmlspecialchars($msg['type']) ?>">
                        <?= $msg['text'] ?>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
