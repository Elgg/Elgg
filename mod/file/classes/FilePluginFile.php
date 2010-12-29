<?php

/**
 * Override the ElggFile
 */
class FilePluginFile extends ElggFile {
	protected function  initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "file";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);
	}
}
