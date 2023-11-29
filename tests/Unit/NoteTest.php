Вопрос 1.
Ответ
Ответ.
<?php


class Worker {
    private string $name;
    private int $age;
    private int $salary;


    public function setName(string $name) :void
    {
        $this->name = $name;
    }

    public function getName() :string
    {
        return $this->name;
    }

    public function setAge(int $age) :void
    {
        if ($this->checkAge($age)) {
            $this->age = $age;
        }

    }

    private function checkAge(int $age) :bool
    {
        return $age > 0 && $age <= 100;
    }

    public function getAge() :int
    {
        return $this->age;
    }

    public function setSalary(int $salary) :void
    {
        $this->salary = $salary;
    }

    public function getSalaty() :int
    {
        return $this->salary;
    }

}

$user1 = new Worker ();
$user1->setName("Иван");
$user1->setAge(25);
$user1->setSalary(1000);

print_r($user1);

$user2 = new Worker ();
$user2->setName("Вася");
$user2->setAge(100);
$user2->setSalary(2000);

print_r($user2);

?>
