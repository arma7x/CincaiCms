<?php

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

include_once('../system/FileLocator.php');
include_once('../system/FileMetadata.php');
include_once('../system/Helper.php');

startTimer();
$data = [];
$base = realpath(__dir__.'/../application');
$file = new FileLocator($_SERVER, $base);
$content = $file->getContent();
$metadata = new FileMetadata(__dir__.'/../application/metadata.json');
if ($content === false) {
	$base = realpath(__dir__.'/../application/pages');
	if (count(explode('/', $_SERVER['PHP_SELF'])) === 2) {
		$_SERVER['PATH_INFO'] = '/home';
	}
	$file = new FileLocator($_SERVER, $base);
	$content = $file->getContent();
	if ($content === false) {
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		$data['title'] = '404 - NOT FOUND';
		$data['content'] = '<h1 class="text-center">404 - NOT FOUND</h1>';
	} else {
		$data['metadata'] = $metadata->seekMetadata($file->target_file);
		$data['title'] = $file->target_file;
		$data['content'] = $content;
	}
} else {
	$data['metadata'] = $metadata->seekMetadata($file->target_file);
	$data['title'] = $file->target_file;
	$data['content'] = $content;
}
$metadata = $metadata->getMetadata();
$data['page_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/pages');
$data['blog_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/blogs');
$data['stat_ram'] = ramUsage();
$data['stat_timer'] = endTimer();
require_once('/home/arma7x/Desktop/New/php/alpha/system/template.php');

