<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа 15</title>
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
        .info {
            color: #036;
            margin: 8px 0;
        }
    </style>
</head>
<body>

<?php
interface AreaCalculatable
{
    public function getArea(): float;
}

abstract class Figure
{
    protected string $color;
    protected float $area;

    public function __construct(string $color = 'white')
    {
        $this->color = $color;
    }

    abstract public function infoAbout(): string;
}

class Rectangle extends Figure implements AreaCalculatable
{
    private float $a;
    private float $b;
    const SIDES_COUNT = 4;

    public function __construct(float $a, float $b, string $color = 'white')
    {
        parent::__construct($color);
        $this->a = $a;
        $this->b = $b;
        $this->area = $a * $b;
    }

    public function getArea(): float
    {
        return $this->area;
    }

    public function infoAbout(): string
    {
        return "Это класс прямоугольника. У него " . self::SIDES_COUNT . " стороны.";
    }
}

class Square extends Figure implements AreaCalculatable
{
    private float $a;
    const SIDES_COUNT = 4;

    public function __construct(float $a, string $color = 'white')
    {
        parent::__construct($color);
        $this->a = $a;
        $this->area = $a * $a;
    }

    public function getArea(): float
    {
        return $this->area;
    }

    public function infoAbout(): string
    {
        return "Это класс квадрата. У него " . self::SIDES_COUNT . " стороны.";
    }
}

class Triangle extends Figure implements AreaCalculatable
{
    private float $a;
    private float $b;
    private float $c;
    const SIDES_COUNT = 3;

    public function __construct(float $a, float $b, float $c, string $color = 'white')
    {
        parent::__construct($color);
        $this->a = $a;
        $this->b = $b;
        $this->c = $c;
        $p = ($a + $b + $c) / 2;
        $this->area = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
    }

    public function getArea(): float
    {
        return $this->area;
    }

    public function infoAbout(): string
    {
        return "Это класс треугольника. У него " . self::SIDES_COUNT . " стороны.";
    }
}

echo "<h3>Лабораторная работа 15. Абстрактные классы и интерфейсы</h3>";

$rect1 = new Rectangle(5, 10);
$rect2 = new Rectangle(3.5, 7.2);
$sq1 = new Square(4);
$sq2 = new Square(6.5);
$tri1 = new Triangle(3, 4, 5);
$tri2 = new Triangle(6, 7, 8);

$figures = [$rect1, $rect2, $sq1, $sq2, $tri1, $tri2];

foreach ($figures as $figure) {
    echo '<div class="info">';
    echo $figure->infoAbout() . '<br>';
    echo 'Площадь: ' . $figure->getArea() . '<br>';
    echo '</div>';
}
?>

</body>
</html>
