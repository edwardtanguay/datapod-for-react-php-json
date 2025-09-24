<?php

class Person {
	public $name;
	public $age;	

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
$person->age = 31; 
echo $person->greet() . "\n";

var_dump($person); // Display the object structure