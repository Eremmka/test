<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа 14</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: #f7f7f7;
            color: #333;
        }
        a {
            margin-right: 15px;
            text-decoration: none;
            color: #036;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        .blog-cards {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }
        .card {
            background: #fff;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            width: 200px;
        }
    </style>
</head>
<body>

<?php
class Page
{
    private string $name;
    private string $template;

    public function __construct(string $name = "page", string $template = "<div><p>It is a default page</p></div>")
    {
        $this->name = $name;
        $this->template = $template;
    }

    public function render(): void
    {
        echo $this->template;
    }
}

class BlogPage extends Page
{
    public function __construct()
    {
        $blogTemplate = '
        <div class="blog-cards">
            <div class="card">
                <h3>Пост 1</h3>
                <p>Текст первой карточки блога.</p>
            </div>
            <div class="card">
                <h3>Пост 2</h3>
                <p>Текст второй карточки блога.</p>
            </div>
            <div class="card">
                <h3>Пост 3</h3>
                <p>Текст третьей карточки блога.</p>
            </div>
        </div>';
        parent::__construct("blog", $blogTemplate);
    }
}

echo '<a href="?page=page">Обычная страница</a>';
echo '<a href="?page=blog">Страница блога</a>';
echo '<br><br>';

$pageParam = $_GET['page'] ?? '';

if ($pageParam === 'blog') {
    $page = new BlogPage();
    $page->render();
} elseif ($pageParam === 'page') {
    $page = new Page();
    $page->render();
} else {
    $page = new Page();
    $page->render();
}
?>

</body>
</html>
