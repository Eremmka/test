<?php
$name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Гость';

$result = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['calc'])) {
    $num1 = floatval($_POST['num1']);
    $num2 = floatval($_POST['num2']);
    $op = $_POST['operation'];
    switch ($op) {
        case '+': $result = $num1 + $num2; break;
        case '-': $result = $num1 - $num2; break;
        case '*': $result = $num1 * $num2; break;
        case '/': $result = ($num2 != 0) ? $num1 / $num2 : 'Ошибка (деление на 0)'; break;
        default: $result = 'Неверная операция';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Калькулятор</title>
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
        .calc-container {
            width: 60%;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .welcome {
            background: #e8f5e9;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            color: #2e7d32;
        }
        h2 { margin-bottom: 20px; color: #333; }
        .inputs {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }
        .inputs input {
            flex: 1;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 6px;
            text-align: center;
        }
        .buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .buttons button {
            background: #4CAF50;
            color: white;
            border: none;
            flex: 1;
            min-width: 70px;
            padding: 10px;
            font-size: 20px;
            font-weight: bold;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }
        .buttons button:hover {
            background: #45a049;
            transform: scale(1.02);
        }
        .result {
            padding: 10px;
            background: #f5f5f5;
            border-radius: 6px;
            font-weight: bold;
            font-size: 18px;
            margin-top: 10px;
        }
        .back {
            margin-top: 20px;
            display: inline-block;
            color: #2196f3;
            text-decoration: none;
        }
        @media (max-width: 480px) {
            .calc-container { padding: 20px; }
            .buttons button { min-width: 60px; font-size: 18px; }
        }
    </style>
</head>
<body>
<div class="calc-container">
    <div class="welcome">
        Привет, <strong><?= $name ?></strong>!
    </div>

    <h2>Калькулятор</h2>

    <form method="POST">
        <div class="inputs">
            <input type="number" name="num1" step="any" placeholder="Число 1" required>
            <input type="number" name="num2" step="any" placeholder="Число 2" required>
        </div>
        <div class="buttons">
            <button type="submit" name="calc" value="+">+</button>
            <button type="submit" name="calc" value="-">-</button>
            <button type="submit" name="calc" value="*">*</button>
            <button type="submit" name="calc" value="/">/</button>
        </div>
        <input type="hidden" name="operation" id="operation" value="+">
    </form>

    <?php if ($result !== ''): ?>
        <div class="result">
            Результат: <strong><?= htmlspecialchars($result) ?></strong>
        </div>
    <?php endif; ?>

</div>

<script>
    const buttons = document.querySelectorAll('.buttons button');
    const opField = document.getElementById('operation');
    buttons.forEach(btn => {
        btn.addEventListener('click', function(e) {
            opField.value = this.value;
        });
    });
</script>
</body>
</html>
