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

	public function storeFile($folder) {
		if ($this->save_path !== '') {
			$save_path = DIRECTORY_SEPARATOR.$this->save_path;
			$folders = explode(' ', $this->save_path);
			if (count($folders) > 1) {
				$save_path = '';
				foreach($folders as $index => $name) {
					$save_path .= DIRECTORY_SEPARATOR.$name;
					if($this->isPathExist(realpath($folder).$save_path) === false) {
						mkdir(realpath($folder).$save_path, 0777, true);
						chmod(realpath($folder).$save_path, 0777);
					}
				}
			} else {
				if($this->isPathExist(realpath($folder).$save_path) === false) {
					mkdir(realpath($folder).$save_path, 0777, true);
					chmod(realpath($folder).$save_path, 0777);
				}
			}
		}
		$success = file_put_contents(realpath($folder).$save_path.DIRECTORY_SEPARATOR.$this->metadata_index.'.html', $this->content);
		if ($success) {
			$this->saveMetadata();
		}
	}

	public function removeFile($folder) {
		
	}

	private function saveMetadata() {
		$data[$this->metadata_index] = [
			'author' => $this->author,
			'title' => $this->title,
			'description' => $this->description,
			'save_path' => $this->save_path,
			'created_at' => $this->created_at,
			'updated_at' => $this->updated_at
		];
		$this->metadata->saveMetadata($data);
	}

}


//$metadata = new FileMetadata(__dir__.'/../application/blogs_metadata.json');

//$file = [
	//'author' => 'arma7x',
	//'title' => 'An Introduction To PHP', 
	//'description' => 'Put some file description here', 
	//'save_path' => 'an-introduction-to-php.html', 
	//'created_at' => time(),
	//'updated_at' => time(),
	//'content' => 'content should be here',
	//'metadata_index' => 'an-introduction-to-php'
//];
//$a = new FileEditor($file, $metadata);
//var_dump($a->metadata->seekMetadata('an-introduction-to-php'));
