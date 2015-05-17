<?php

/**
 * Loyal North Risk Profiling System
 *
 * @author Alla Mamontova <allasergeevna@list.ru>
 *
 * @version 1.0 06.07.2014
 */
error_reporting(E_ALL);

define('DIRSEP', DIRECTORY_SEPARATOR);
$site_path = realpath(dirname(__FILE__) . DIRSEP . '.' . DIRSEP) . DIRSEP;
define('SITE_PATH', $site_path);

function __autoload($class_name) {
	$filename = strtolower($class_name) . '.php';
	$file = SITE_PATH . 'classes' . DIRSEP . $filename;
	$file_exists = file_exists($file);
	$folders = get_subfolders(SITE_PATH . 'classes', array());
	foreach ($folders as $folder) {
		if (!$file_exists) {
			$file = $folder . DIRSEP . $filename;
			$file_exists = file_exists($file);
		}
	}

	if ($file_exists === false) {
		return false;
	}
	include ($file);
}

function get_subfolders($folder, $folders) {
	if ($objs = glob($folder . DIRSEP . '*')) {
		foreach ($objs as $obj) {
			if (is_dir($obj)) {
				$folders = get_subfolders($obj, $folders);
				$folders[] = $obj;
			}
		}
	}
	return $folders;
}

require_once(SITE_PATH . 'config.php');

registry::init();
registry::set('css_directory', 'css');
registry::set('scripts_directory', 'scripts');

if ($_SERVER['HTTP_HOST'] == WWW . SITE_HOST) {
	registry::set('subdomain', '');
	registry::set('controllers_directory', 'controllers');
	registry::set('templates_directory', 'templates');
} else {
	header("Location: http://" . WWW . SITE_HOST . $_SERVER['REQUEST_URI']);
	die();
}

registry::set('default_controller', 'index');

db::init();

$url = parse_url($_SERVER['REQUEST_URI']);
registry::set('route', $url['path']);

if ($url['path'] == '/sitemap.xml'){
	include registry::get('templates_directory').DIRSEP.'sitemap.php';
}
elseif ($url['path'] == '/robots.txt'){
	include registry::get('templates_directory').DIRSEP.'robots.php';
}
else{
	user::init();

	$router = new router();
	$router->delegate();
}
?>
