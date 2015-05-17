<?php

class script {

    private $defer = false;
    private $path;

    public function __construct() {

    }

    public function set_path($path) {
	$this->path = $path;
    }

    public function set_defer() {
	$this->defer = true;
    }

    public function is_defer() {
	return $this->defer;
    }

    public function get_html_include() {
	$html_include = '';
	$file = registry::get('scripts_directory') . DIRSEP . $this->path . '.js';
	$common_file = registry::get('scripts_directory') . DIRSEP . $this->path . '.js';
	if (file_exists(SITE_PATH . $file)) {
	    $timestamp = filemtime($file);
	    $file = str_replace(DIRSEP, '/', DIRSEP . $file);
	    $html_include = '<script  type="text/javascript" src="' . $file . '?' . $timestamp . '"></script>';
	} elseif (file_exists(SITE_PATH . $common_file)) {
	    $timestamp = filemtime($common_file);
	    $file = str_replace(DIRSEP, '/', DIRSEP . $common_file);
	    $html_include = '<script  type="text/javascript" src="' . $file . '?' . $timestamp . '"></script>';
	}
	return $html_include;
    }

}

?>