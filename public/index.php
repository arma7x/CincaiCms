<?php

	include_once('../system/DevScript.php');
	include_once('../system/FileLocator.php');
	include_once('../system/FileMetadata.php');
	include_once('../system/Helper.php');

	startTimer();
	$data = [];
	$base = realpath(__dir__.'/../application');
	$file = new FileLocator($_SERVER, $base);
	$blogs_metadata = new FileMetadata(__dir__.'/../application/blogs_metadata.json');
	$pages_metadata = new FileMetadata(__dir__.'/../application/pages_metadata.json');
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
			$data['content'] = '<div class="row justify-content-sm-center align-items-center" style="height:70vh;"><h1 class="text-center">404 - NOT FOUND</h1></div>';
			$data['metadata'] = [];
		} else {
			$data['metadata'] = $pages_metadata->seekMetadata($file->target_file);
			$data['title'] = $file->target_file;
			$data['content'] = $content;
		}
	} else {
		$data['metadata'] = $blogs_metadata->seekMetadata($file->target_file);
		$data['title'] = $file->target_file;
		$data['content'] = $content;
	}
	$data['blogs_metadata'] = $blogs_metadata->getMetadata();
	$data['pages_metadata'] = $pages_metadata->getMetadata();
	$data['page_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/pages');
	$data['blog_tree'] = FileLocator::getDirectoryTree(__dir__.'/../application/blogs');
	$data['page_ordering'] = unserialize(file_get_contents(__dir__.'/../application/pages_ordering.json'));
	require_once('../application/template/public_template.php');

