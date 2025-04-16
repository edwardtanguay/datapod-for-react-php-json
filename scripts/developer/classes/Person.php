<?php
class Person
{
	public $name;
	public $age;
	public $gender;

	public function __construct($name, $age, $gender)
	{
		$this->name = $name;
		$this->age = $age;
		$this->gender = $gender;
	}

	public function __toString()
	{
		return $this->name . " is " . $this->age . " years old and is a " . $this->gender;
	}
}
