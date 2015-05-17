<?php
class header
{

	public static function set_error($number){
		if (strnatcmp($number,'404')==0){
			header('HTTP/1.1 404 Not Found');
		}
		elseif (strnatcmp($number,'500')==0){
			header('HTTP/1.1 500 Internal Server Error');
		}
		else{
			header('HTTP/1.1 404 Not Found');
		}
	}

	public static function set_charset($charset){
		header("Content-type: text/html; charset=".$charset);
		die;
	}

	public static function set_location($location){
		header("Location: ".$location);
		die;
	}
}


?>