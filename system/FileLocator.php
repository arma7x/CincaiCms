<?php

require_once('Trait.php');

class FileLocator {

	use Commons;

	protected $base_folder = '';
	protected $request_query = [];
	protected $target_folder = '';
	protected $target_file = '';
	protected $absolute_file_path = '';

	public function __construct($SERVER, $base_folder) {
		$this->request_query = explode('/', ltrim(isset($SERVER['PATH_INFO']) ? $SERVER['PATH_INFO'] : $SERVER['REQUEST_URI'], '/'));
		$this->base_folder = realpath($base_folder);
		$this->exec();
	}

	private function resolvePath() {
		foreach($this->request_query as $index => $folder) {
			if ($index === (count($this->request_query) - 1)) {
				$this->target_file = $folder;
			} else {
				$this->target_folder .= DIRECTORY_SEPARATOR.$folder;
			}
		}
		return $this;
	}

	private function getPathToFile() {
		if ($dh = opendir($this->base_folder.$this->target_folder)) {
			while (($file = readdir($dh)) !== false) {
				$filename = explode('.', $file); //emit file ext in search
				if (filetype($this->base_folder.$this->target_folder.DIRECTORY_SEPARATOR.$file) !== 'dir' && $this->target_file == $filename[0]) {
					$this->absolute_file_path = realpath($this->base_folder.$this->target_folder.DIRECTORY_SEPARATOR.$file);
					return true;
				}
			}
			closedir($dh);
			return false;
		}
		return false;
	}

	private function exec() {
		$this->resolvePath();
		if (is_dir(realpath($this->base_folder.$this->target_folder))) {
			$this->getPathToFile();
		}
	}

	public function getContent() {
		if ($this->absolute_file_path === '')
			return false;
		return file_get_contents($this->absolute_file_path, FILE_USE_INCLUDE_PATH);
	}

	public static function getDirectoryTree($dir) {
		$tree = [];
		$dir = realpath($dir);
		foreach(scandir($dir) as $index => $name) {
			if ($index > 1) {
				if (filetype($dir.DIRECTORY_SEPARATOR.$name) === 'dir' && self::is_dir_empty($dir.DIRECTORY_SEPARATOR.$name) === false) {
					array_push($tree, [$name => self::getDirectoryTree($dir.'/'.$name)]);
				} elseif (filetype($dir.DIRECTORY_SEPARATOR.$name) !== 'dir') {
					array_push($tree, $name);
				}
			}
		}
		return $tree;
	}
}
