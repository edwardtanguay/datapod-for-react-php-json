<?php

class Person {
	private $name;
	private $age;

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function getAge() {
		return $this->age;
	}

	public function setAge($age) {
		$this->age = $age;
	}

	public function __construct($name, $age) {
		$this->name = $name;
		$this->age = $age;
	}

	public function greet() {
		return "Hello, my name is {$this->name} and I am {$this->age} years old.";
	}
}

// Example usage
$person = new Person("Alice", 30);
echo $person->greet() . "\n"; 
$person->setAge(31);
echo $person->greet() . "\n";

var_dump($person); // Display the object structure