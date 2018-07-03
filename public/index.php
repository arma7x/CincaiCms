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
$pages_metadata = new FileMetadata(__dir__.'/../application/blogs_metadata.json');
$content = $file->getContent();
if ($content === false) {
	$base = realpath(__dir__.'/../application/pages');
	if ($_SERVER['REQUEST_URI'] === '/') {
		$_SERVER['REQUEST_URI'] = '/home';
	}
	$file = new FileLocator($_SERVER, $base);
	$content = $file->getContent();
	if ($content === false) {
		header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
		$data['title'] = '404 - NOT FOUND';
		$data['content'] = '<h1 class="text-center">404 - NOT FOUND</h1>';
	} else {
		$pages_metadata = new FileMetadata(__dir__.'/../application/pages_metadata.json');
		$data['metadata'] = $pages_metadata->seekMetadata($file->target_file);
		$data['title'] = $file->target_file;
		$data['content'] = $content;
	}
} else {
	$data['metadata'] = $pages_metadata->seekMetadata($file->target_file);
	$data['title'] = $file->target_file;
	$data['content'] = $content;
}
$metadata = $pages_metadata->getMetadata();
$data['page_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/pages');
$data['blog_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/blogs');
$data['page_ordering'] = unserialize(file_get_contents(__dir__.'/../application/pages_ordering.js'));
require_once('../application/template/public_template.php');

