<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .form-container {
            max-width: 450px;
            width: 100%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 { text-align: center; margin-bottom: 25px; color: #333; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background: #45a049; }
        .login-link { text-align: center; margin-top: 20px; }
        .login-link a { color: #4CAF50; text-decoration: none; }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Регистрация</h2>
    <form method="POST" action="action.php">
        <div class="form-group">
            <label>Имя:</label>
            <input type="text" name="name" placeholder="Введите имя" required>
        </div>
        <div class="form-group">
            <label>Почта:</label>
            <input type="email" name="email" placeholder="name@example.ru" required>
        </div>
        <div class="form-group">
            <label>Пол:</label>
            <select name="gender" required>
                <option value="" disabled selected>Выберите пол</option>
                <option value="male">Мужской</option>
                <option value="female">Женский</option>
                <option value="other">Другой</option>
            </select>
        </div>
        <div class="form-group">
            <label>Пароль:</label>
            <input type="password" name="password" placeholder="Введите пароль" required>
        </div>
        <div class="form-group">
            <label>Подтвердите пароль:</label>
            <input type="password" name="password_confirm" placeholder="Повторите пароль" required>
        </div>
        <button type="submit">Зарегистрироваться</button>
        <div class="login-link">
            Уже есть аккаунт? <a href="login.php">Войти</a>
        </div>
        <div style="font-size:12px; text-align:center; color:#777; margin-top:15px;">
            Создавая учетную запись, вы соглашаетесь с нашими условиями.
        </div>
    </form>
</div>
</body>
</html>
