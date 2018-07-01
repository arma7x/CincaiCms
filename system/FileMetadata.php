<?php

class FileMetadata {

	protected $metadata_file = '';
	protected $metadata = '';

	public function __construct($metadata_file) {
		$this->metadata_file = realpath($metadata_file);
		$this->metadata = $this->getMetadata();
	}

	public function getMetadata() {
		$raw = file_get_contents(realpath($this->metadata_file), FILE_USE_INCLUDE_PATH);
		$this->metadata = json_decode($raw, true);
		return $this->metadata;
	}

	public function seekMetadata($index) {
		return isset($this->metadata[$index]) ? $this->metadata[$index] : [];
	}

	public function saveMetadata($data) {
		foreach ($data as $index => $metadata) {
			$this->metadata[$index] = $metadata;
		}
		file_put_contents(realpath($this->metadata_file),json_encode($this->metadata));
		$this->metadata = $this->getMetadata();
		return $this->metadata;
	}

	public function removeMetadata($index) {
		unset($this->metadata[$index]);
		$this->metadata = $this->getMetadata();
		return $this->metadata;
	}
}

//$a = new FileMetadata('/home/arma7x/Desktop/New/php/alpha/application/metadata.json');
//$old = $a->seekMetadata('an-introduction-to-php');
//var_dump($old);
//echo '#######################'.PHP_EOL;
////$new['an-introduction-to-php'] = [
	////'author' => 'arma7x',
	////'title' => 'An Introduction To PHP', 
	////'description' => 'Put some file description here', 
	////'created_at' => time(),
	////'updated_at' => time()
////];
//$new['an-introduction-to-php-part-2'] = [
	//'author' => 'arma7x',
	//'title' => 'An Introduction To PHP - Part 2', 
	//'description' => 'Put some file description here', 
	//'created_at' => time(),
	//'updated_at' => time()
//];
//$a->saveMetadata($new);
//var_dump($a->seekMetadata('an-introduction-to-php-part-2'));
