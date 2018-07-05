<?php

class FileMetadata {

	protected $metadata_file = '';
	protected $metadata = '';

	public function __construct($metadata_file) {
		$this->metadata_file = realpath($metadata_file);
		$this->metadata = $this->getMetadata();
		return $this;
	}

	public function getMetadata() {
		$raw = file_get_contents(realpath($this->metadata_file), FILE_USE_INCLUDE_PATH);
		$this->metadata = json_decode($raw, true);
		return $this->metadata ? $this->metadata : [];
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
		file_put_contents(realpath($this->metadata_file),json_encode($this->metadata));
		$this->metadata = $this->getMetadata();
		return $this->metadata;
	}
}
