<?php
$menuItems = [
    'Главная' => '/index.php',
    'О нас' => '/pages/about.php',
    'Контакты' => '/pages/contact.php'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <title>Мой сайт</title>
    <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header>
    <nav>
        <ul>
            <?php foreach ($menuItems as $title => $url): ?>
            <li>
                <a href="<?= $url ?>"<?= $_SERVER['REQUEST_URI'] == $url ? ' class="active"' : '' ?>>
                    <?= $title ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
     <nav>
</header>
