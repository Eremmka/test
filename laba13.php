<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа 13</title>
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
        }
    </style>
</head>
<body>

<?php
class Worker
{
    private $name;
    private $age;
    private $salary;
    private static $totalSalary = 0;

    public function __construct($name, $age, $salary)
    {
        $this->name = $name;
        $this->age = $age;
        $this->salary = $salary;
        self::$totalSalary += $salary;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function getSalary()
    {
        return self::$totalSalary;
    }

    public function setAge($newAge)
    {
        if ($this->checkAge($newAge)) {
            $this->age = $newAge;
        }
    }

    private function checkAge($age)
    {
        if ($age >= 18) {
            $this->age = $age;
            return true;
        } else {
            echo "<span class='info'>Вам работать в нашей компании еще рано</span><br>";
            return false;
        }
    }
}

echo "<h3>Работа с объектами класса Worker</h3>";

$worker1 = new Worker("Иван", 25, 50000);
$worker2 = new Worker("Мария", 30, 60000);

echo "<span class='info'>Сумма зарплат: " . ($worker1->getSalary()) . "</span><br>";
echo "<span class='info'>Сумма возрастов: " . ($worker1->getAge() + $worker2->getAge()) . "</span><br>";

echo "<span class='info'>Имя первого работника: " . $worker1->getName() . "</span><br>";
echo "<span class='info'>Возраст второго работника: " . $worker2->getAge() . "</span><br>";
echo "<span class='info'>Зарплата (общая сумма через getSalary): " . $worker1->getSalary() . "</span><br>";

echo "<br><span class='info'>Проверка setAge (возраст 17):</span><br>";
$worker1->setAge(17);
echo "<span class='info'>Текущий возраст работника после попытки: " . $worker1->getAge() . "</span><br>";

echo "<br><span class='info'>Проверка setAge (возраст 20):</span><br>";
$worker1->setAge(20);
echo "<span class='info'>Текущий возраст работника после изменения: " . $worker1->getAge() . "</span><br>";
?>

</body>
</html>
