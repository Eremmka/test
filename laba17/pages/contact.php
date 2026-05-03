<?php include __DIR__ . '/../includes/header.php'; ?>
<main>
    <h1>Контакты</h1>
    <form method="post">
        <input type="text" name="name" placeholder="Имя" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="message" placeholder="Сообщение" required></textarea>
        <button type="submit">Отправить</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = htmlspecialchars($_POST['name'] ?? '');
        $email = htmlspecialchars($_POST['email'] ?? '');
        $message = htmlspecialchars($_POST['message'] ?? '');
        echo '<div class="form-result">';
        echo '<h3>Спасибо, ' . $name . '!</h3>';
        echo '<p>Email: ' . $email . '</p>';
        echo '<p>Сообщение: ' . $message . '</p>';
        echo '</div>';
    }
    ?>
</main>
<?php include __DIR__ . '/../includes/footer.php'; ?>
