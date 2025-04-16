<?php
require_once "../qtools/qcli.php";
require_once "person.php";

class Learn
{
  public static function title($title)
  {
    qcli::message($title, "info");
  }
  public static function line($line)
  {
    qcli::message($line, "data");
  }
  public static function ex001()
  {
    Learn::title("Dates and Times");
    Learn::line("1. today is " . date("Y-m-d"));
    Learn::line("2. today is " . date("l"));
    Learn::line("3. today is " . date('l, jS \of F Y, h:i:s A'));
    Learn::line("4. today is " . date('l, jS \of F Y, G:i:s')); // G = 4, H = 04
    Learn::line("5. the time is " . date('h:i:s a'));
    Learn::line("6. the time is " . date('H:i:s a'));
  }

  public static function ex002()
  {
    Learn::title("Basic class");
    $person = new Person('John', 30, 'male');
    qcli::message($person,"data");
  }
}
