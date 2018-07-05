<?php
	session_start();

	if ($_SESSION['admin'] !== true) {
		$_SESSION['flash']['warning'] = 'Forbidden Access';
		header('Location: /admin/index.php');
		die;
	}

	require_once('../../system/admin/info.php');
