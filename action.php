<?php

$usersFile = __DIR__ . '/users.txt';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Доступ запрещён');
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$gender = $_POST['gender'] ?? '';
$password = $_POST['password'] ?? '';
$confirm = $_POST['password_confirm'] ?? '';

if (empty($email)) {
    $errors['email'] = 'Поле "Почта" обязательно для заполнения';
}
if (empty($password)) {
    $errors['password'] = 'Поле "Пароль" обязательно для заполнения';
}

if (empty($name)) $errors['name'] = 'Имя обязательно';
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'Некорректный формат email';
}
if (empty($gender)) $errors['gender'] = 'Выберите пол';
if (!empty($password) && strlen($password) < 6) {
    $errors['password'] = 'Пароль должен содержать не менее 6 символов';
}
if ($password !== $confirm) {
    $errors['password_confirm'] = 'Пароли не совпадают';
}

if (!empty($errors)) {
    echo "<!DOCTYPE html><html><head><title>Ошибка регистрации</title>";
    echo "<style>body{font-family:Arial;padding:20px;} .error{color:red;}</style></head><body>";
    echo "<h2>Ошибка регистрации</h2><ul>";
    foreach ($errors as $field => $msg) {
        echo "<li class='error'>$msg</li>";
    }
    echo "</ul><a href='index.php'>Вернуться к форме</a>";
    echo "</body></html>";
    exit;
}

$emailExists = false;
if (file_exists($usersFile)) {
    $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        list($storedEmail) = explode('|', $line);
        if (strcasecmp($storedEmail, $email) === 0) {
            $emailExists = true;
            break;
        }
    }
}
if ($emailExists) {
    die("<h2>Ошибка</h2><p>Пользователь с таким email уже зарегистрирован.</p><a href='index.php'>Назад</a>");
}

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$newLine = implode('|', [$email, $name, $gender, $hashedPassword]) . PHP_EOL;

$result = file_put_contents($usersFile, $newLine, FILE_APPEND | LOCK_EX);
if ($result === false) {
    die("<h2>Ошибка сервера</h2><p>Не удалось сохранить данные. Проверьте права на запись в папке.</p><a href='index.php'>Назад</a>");
}

header('Location: success.php?name=' . urlencode($name));
exit;
