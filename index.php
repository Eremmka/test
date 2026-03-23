<?php
$usersFile = __DIR__ . '/users.txt';
$errors = [];
$name = $email = $gender = '';

if (!file_exists($usersFile)) {
    @touch($usersFile);
    @chmod($usersFile, 0666);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['password_confirm'] ?? '';

    if (empty($name)) $errors['name'] = 'Имя обязательно';
    if (empty($email)) $errors['email'] = 'Почта обязательна';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Некорректный email';
    if (empty($gender)) $errors['gender'] = 'Выберите пол';
    if (empty($password)) $errors['password'] = 'Пароль обязателен';
    elseif (strlen($password) < 6) $errors['password'] = 'Минимум 6 символов';
    if ($password !== $confirm) $errors['password_confirm'] = 'Пароли не совпадают';

    if (empty($errors)) {
        $emailExists = false;
        if (file_exists($usersFile) && filesize($usersFile) > 0) {
            $lines = file($usersFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            if ($lines !== false) {
                foreach ($lines as $line) {
                    $parts = explode('|', $line);
                    if (count($parts) >= 1 && strcasecmp(trim($parts[0]), $email) === 0) {
                        $emailExists = true;
                        break;
                    }
                }
            }
        }
        if ($emailExists) {
            $errors['email'] = 'Email уже зарегистрирован';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $newLine = implode('|', [$email, $name, $gender, $hashedPassword]) . PHP_EOL;
            if (file_put_contents($usersFile, $newLine, FILE_APPEND | LOCK_EX) !== false) {
                header('Location: action.php');
                exit;
            } else {
                $errors['general'] = 'Не удалось сохранить данные. Проверьте права на папку и файл.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .register-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 32px;
            width: 100%;
            max-width: 480px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 8px;
            color: #1a1a1a;
            text-align: center;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 24px;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            font-size: 14px;
        }

        input, select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        input:focus, select:focus {
            outline: none;
            border-color: #4a90e2;
            box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
        }

        .error-feedback {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 4px;
        }

        button {
            background: #4a90e2;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: background 0.2s;
            margin: 8px 0 16px;
        }

        button:hover {
            background: #357abd;
        }

        .agreement, .login-link {
            text-align: center;
            font-size: 13px;
            color: #666;
        }

        .agreement a, .login-link a {
            color: #4a90e2;
            text-decoration: none;
        }

        .agreement a:hover, .login-link a:hover {
            text-decoration: underline;
        }

        .agreement {
            margin-top: 8px;
            padding-top: 16px;
            border-top: 1px solid #eee;
        }

        .login-link {
            margin-top: 12px;
        }

        .general-error {
            background: #fee;
            border-left: 4px solid #e74c3c;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #c0392b;
            font-size: 14px;
        }
    </style>
</head>
<body>
<div class="register-card">
    <h2>Регистрация</h2>
    <div class="subtitle">Создайте новый аккаунт</div>

    <?php if (!empty($errors['general'])): ?>
        <div class="general-error"><?= htmlspecialchars($errors['general']) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label>Имя</label>
            <input type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars($name) ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="error-feedback"><?= htmlspecialchars($errors['name']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Почта</label>
            <input type="email" name="email" placeholder="name@example.ru" value="<?= htmlspecialchars($email) ?>">
            <?php if (isset($errors['email'])): ?>
                <div class="error-feedback"><?= htmlspecialchars($errors['email']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Пол</label>
            <select name="gender">
                <option value="" disabled <?= empty($gender) ? 'selected' : '' ?>>Выберите пол</option>
                <option value="male" <?= $gender === 'male' ? 'selected' : '' ?>>Мужской</option>
                <option value="female" <?= $gender === 'female' ? 'selected' : '' ?>>Женский</option>
            </select>
            <?php if (isset($errors['gender'])): ?>
                <div class="error-feedback"><?= htmlspecialchars($errors['gender']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Пароль</label>
            <input type="password" name="password" placeholder="Введите пароль">
            <?php if (isset($errors['password'])): ?>
                <div class="error-feedback"><?= htmlspecialchars($errors['password']) ?></div>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label>Подтвердите пароль</label>
            <input type="password" name="password_confirm" placeholder="Повторите пароль">
            <?php if (isset($errors['password_confirm'])): ?>
                <div class="error-feedback"><?= htmlspecialchars($errors['password_confirm']) ?></div>
            <?php endif; ?>
        </div>

        <button type="submit">Зарегистрироваться</button>

        <div class="agreement">
            Создавая учетную запись, вы соглашаетесь с нашим
            <a href="#">Условием</a> и <a href="#">конфиденциальностью</a>.
        </div>
    </form>
</div>
</body>
</html>
