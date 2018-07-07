<?php

require_once('Trait.php');

class FileEditor {

	use Commons;

	public $metadata;
	protected $metadata_index;
	protected $author;
	protected $title;
	protected $description;
	protected $save_path;
	protected $created_at;
	protected $updated_at;
	protected $content;

	public function __construct($attrb, $metadata) {
		$this->metadata = $metadata;
		foreach($attrb as $key => $value) {
			if (self::isPropertyExists($key)) {
				$this->$key = $value;
			}
		}
	}

	public function getContent($folder) {
		$this->content = file_get_contents(realpath($folder).FileEditor::FolderFriendlyToSystem($this->save_path).DIRECTORY_SEPARATOR.$this->metadata_index.'.html');
		return $this;
	}

	public function storeFile($folder) {
		$save_path = '';
		if ($this->save_path !== '') {
			$save_path = DIRECTORY_SEPARATOR.$this->save_path;
			$folders = explode(' ', $this->save_path);
			if (count($folders) > 1) {
				$save_path = '';
				foreach($folders as $index => $name) {
					$save_path .= DIRECTORY_SEPARATOR.$name;
					if(is_dir(realpath($folder).$save_path) === false) {
						mkdir(realpath($folder).$save_path, 0777, true);
						chmod(realpath($folder).$save_path, 0777);
					}
				}
			} else {
				if(is_dir(realpath($folder).$save_path) === false) {
					mkdir(realpath($folder).$save_path, 0777, true);
					chmod(realpath($folder).$save_path, 0777);
				}
			}
		}
		$success = file_put_contents(realpath($folder).$save_path.DIRECTORY_SEPARATOR.$this->metadata_index.'.html', $this->content);
		if ($success) {
			chmod(realpath($folder).$save_path.DIRECTORY_SEPARATOR.$this->metadata_index.'.html', 0777);
			$data[$this->metadata_index] = [
				'author' => $this->author,
				'title' => $this->title,
				'description' => $this->description,
				'save_path' => $this->save_path,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at
			];
			$this->metadata->saveMetadata($data);
			if (count($this->metadata->seekMetadata($this->metadata_index)) > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function removeFile($folder) {
		$save_path = '';
		if ($this->save_path !== '') {
			$save_path = DIRECTORY_SEPARATOR.$this->save_path;
			$folders = explode(' ', $this->save_path);
			if (count($folders) > 1) {
				$save_path = '';
				foreach($folders as $index => $name) {
					$save_path .= DIRECTORY_SEPARATOR.$name;
				}
			}
		}
		unlink(realpath($folder).$save_path.DIRECTORY_SEPARATOR.$this->metadata_index.'.html');
		$this->metadata->removeMetadata($this->metadata_index);
		if (count($this->metadata->seekMetadata($this->metadata_index)) === 0) {
			return true;
		} else {
			return false;
		}
	}

	public function editFile() {
		
	}

	static public function TitleFriendly($string) {
		return strtolower(str_replace(' ', '-', preg_replace("/[^[:alnum:][:space:]]/u", '', trim($string))));
	}

	static public function FolderFriendly($string) {
		return strtolower(preg_replace("/[^[:alnum:][:space:][-]/u", '', trim($string)));
	}

	static public function FolderFriendlyToURL($string) {
		$trimed = str_replace(' ', '/', $string);
		return ($trimed !== '') ? '/'.$trimed : $trimed;
	}

	static public function FolderFriendlyToSystem($string) {
		$trimed = str_replace(' ', DIRECTORY_SEPARATOR, $string);
		return ($trimed !== '') ? DIRECTORY_SEPARATOR.$trimed : $trimed;
	}

}
















