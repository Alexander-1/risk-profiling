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
 * Client panel - Support Controller
 * 
 * Just for test, must be removed later
 * @author Kirill Levitin <kirill.levitin@ubn24.de>
 * @version 1.0 2010
 */
class random{
	
	private $numeric=array('0','1','2','3','4','5','6','7','8','9');
	private $alpha=array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	
	function random(){
	}
	
	
	public function get_numeric($length){
		$content='';
		
		for($i=0; $i<$length; $i++){
			$content=$content.get_from_array($this->numeric);
		}
		
		return $content;
	}
	
	
	public function get_alpha($length){
		$content='';
		
		for($i=0; $i<$length; $i++){
			$content=$content.$this->get_from_array($this->alpha);
		}
		
		return $content;
	}
	
	
	public function get_alpha_numeric($length){
	
		$alpha_numeric=array();
		for($i=0; $i<count($this->numeric); $i++){
			$alpha_numeric[count($alpha_numeric)]=$this->numeric[$i];
		}
		for($i=0; $i<count($this->alpha); $i++){
			$alpha_numeric[count($alpha_numeric)]=$this->alpha[$i];
		}
	
		$content='';
		
		for($i=0; $i<$length; $i++){
			$content=$content.$this->get_from_array($alpha_numeric);
		}
		
		return $content;
	}
	
	
	private function get_from_array($ar){
		$r = round(rand(1,100));
		if (isset($ar[round( (count($ar)/100)*$r  )])) return $ar[round( (count($ar)/100)*$r  )];
		else return 0;
	}
	
}
?>