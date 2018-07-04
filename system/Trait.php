<?php

trait Commons {

	public function isPathExist($path) {
		return is_dir($path);
	}

	public function is_dir_empty($dir) {
		$dir = realpath($dir);
		if (!is_readable($dir)) 
			return true; 
		return (count(scandir($dir)) === 2);
	}

	public function isPropertyExists($key) {
		return property_exists(__CLASS__, $key);
	}

	public function __get($key) {
		if (isset($this->$key))
			return $this->$key;
		return null;
	}

	public function __set($key, $value) {
		if (isset($this->$key)) {
			$this->$key = $value;
			return $this->$key;
		}
		return null;
	}

}
