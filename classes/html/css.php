<?php

class css {

    private $path, $media;

    public function __construct($media) {
	$this->media = $media;
    }

    public function set_path($path) {
	$this->path = $path;
    }

    public function get_html_include() {
	$html_include = '';
	$file = registry::get('css_directory') . DIRSEP . $this->path . '.css';
	$common_file = registry::get('css_directory') . DIRSEP . $this->path . '.css';
	if (file_exists(SITE_PATH . $file)) {
	    $timestamp = filemtime($file);
	    $file = str_replace(DIRSEP, '/', DIRSEP . $file);
	    $html_include = '<link rel="stylesheet" type="text/css" href="' . $file . '?' . $timestamp . '" ' . ($this->media != '' ? "media='{$this->media}'" : '') . '>';
	} elseif (file_exists(SITE_PATH . $common_file)) {
	    $timestamp = filemtime($common_file);
	    $file = str_replace(DIRSEP, '/', DIRSEP . $common_file);
	    $html_include = '<link rel="stylesheet" type="text/css" href="' . $file . '?' . $timestamp . '">';
	}
	return $html_include;
    }

}

?>