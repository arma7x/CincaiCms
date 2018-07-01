<?php

class FileEditor {

	protected $author;
	protected $title;
	protected $description;
	protected $created_at;
	protected $updated_at;
	protected $content;

}

require_once('FileLocator.php');
$blog_tree = FileLocator::getDirectoryTree(__dir__.'/../application/pages');
//var_dump($blog_tree);
$ordering = [
	0 => [
		'index'=> 2,
		'ico' => 'fa-home',
	],
	1 => [
		'index'=> 0,
		'ico' => 'fa-network',
	],
	2 => [
		'index'=> 1,
		'ico' => 'fa-info-o',
	],
];
echo '###################'.PHP_EOL;
foreach($ordering as $index => $value) {
	if (isset($blog_tree[$value['index']])) {
		$ordering[$index]['data'] = $blog_tree[$value['index']];
	} else {
		unset($ordering[$index]);
	}
}
var_dump($ordering);
//var_dump(json_encode($ordering, JSON_PRETTY_PRINT));
//var_dump(serialize($ordering));
//var_dump(unserialize(serialize($ordering)));
