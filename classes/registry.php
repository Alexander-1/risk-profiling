<?php
/**
 * VDC 24
 *
 * Cloud hosting interface
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 * @package vdc
 */

/**
 * Substitute array of global variables
 *
 * @author Alla Mamontova <alla.mamontova@ubn24.de>
 * @version 1.0 2011
 */
class registry implements ArrayAccess
{
  private static $init;
  private static $vars = array();

	private function __construct(){
  }

  public static function init(){
    if (self::$init === null) {
      self::$init = new self();
    }
    return self::$init;
	}

  public static function set($key, $var){
    self::$vars[$key] = $var;
    return true;
  }

  public static function get($key){
    if (isset(self::$vars[$key]) == false){
      return null;
    }
    return self::$vars[$key];
  }


  public static function remove($key){
    unset(self::$vars[$key]);
  }

  public function offsetExists($offset){
    return isset($this->vars[$offset]);
  }

  public function offsetGet($offset){
    return $this->get($offset);
  }

  public function offsetSet($offset, $value){
    $this->set($offset, $value);
  }

  public function offsetUnset($offset){
    unset($this->vars[$offset]);
  }

  public function alert(){
    die(var_dump(self::$vars));
  }
}

?>
