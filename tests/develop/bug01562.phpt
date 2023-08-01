--TEST--
Test for bug #1562: Variables with xdebug_get_function_stack
--INI--
xdebug.mode=develop
xdebug.auto_profile=0
xdebug.var_display_max_depth=4
--FILE--
<?php
class Elephpant
{
	function __construct(private string $title, private float $PIE) {}

	function __toString()
	{
		return "{$this->title} loves {$this->PIE}";
	}
}

class Error_Class
{
	public static function getBT()
	{
		$tmp = xdebug_get_function_stack( ['local_vars' => true ] );
		var_dump($tmp);

		echo $tmp[2]['variables']['elephpant'], "\n";
	}

	public static function newError($errno = false)
	{
		$elephpant = new Elephpant("Bluey", M_PI);
		$randoVar = 42;
		return self::getBT();
	}

}

class Error_Entry
{
	public function __construct($base, $errno)
	{
		$return = Error_Class::newError(true);
	}
}

$e = new Error_Entry(1, 2);
?>
--EXPECTF--
%sbug01562.php:17:
array(4) {
  [0] =>
  array(5) {
    'function' =>
    string(6) "{main}"
    'file' =>
    string(62) "%sbug01562.php"
    'line' =>
    int(0)
    'params' =>
    array(0) {
    }
    'variables' =>
    array(1) {
      'e' =>
      NULL
    }
  }
  [1] =>
  array(7) {
    'function' =>
    string(11) "__construct"
    'type' =>
    string(7) "dynamic"
    'class' =>
    string(11) "Error_Entry"
    'file' =>
    string(62) "%sbug01562.php"
    'line' =>
    int(39)
    'params' =>
    array(2) {
      'base' =>
      string(1) "1"
      'errno' =>
      string(1) "2"
    }
    'variables' =>
    array(2) {
      'base' =>
      int(1)
      'errno' =>
      int(2)
    }
  }
  [2] =>
  array(7) {
    'function' =>
    string(8) "newError"
    'type' =>
    string(6) "static"
    'class' =>
    string(11) "Error_Class"
    'file' =>
    string(62) "%sbug01562.php"
    'line' =>
    int(35)
    'params' =>
    array(1) {
      'errno' =>
      string(4) "TRUE"
    }
    'variables' =>
    array(2) {
      'errno' =>
      bool(true)
      'elephpant' =>
      class Elephpant#2 (2) {
        private string $title =>
        string(5) "Bluey"
        private float $PIE =>
        double(3.1415926535898)
      }
    }
  }
  [3] =>
  array(7) {
    'function' =>
    string(5) "getBT"
    'type' =>
    string(6) "static"
    'class' =>
    string(11) "Error_Class"
    'file' =>
    string(62) "%sbug01562.php"
    'line' =>
    int(26)
    'params' =>
    array(0) {
    }
    'variables' =>
    array(1) {
      'tmp' =>
      NULL
    }
  }
}
Bluey loves 3.1415926535%s
