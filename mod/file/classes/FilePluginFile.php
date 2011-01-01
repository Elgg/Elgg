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

	public function delete() {

		$thumbnails = array($this->thumbnail, $this->smallthumb, $this->largethumb);
		foreach ($thumbnails as $thumbnail) {
			if ($thumbnail) {
				$delfile = new ElggFile();
				$delfile->owner_guid = $this->owner_guid;
				$delfile->setFilename($thumbnail);
				$delfile->delete();
			}
		}

		return parent::delete();
	}
}
