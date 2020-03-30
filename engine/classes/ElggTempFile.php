<?php

use Elgg\Exceptions\Filesystem\IOException;

/**
 * This class represents a physical file (by default in the system temp directory).
 *
 * Create a new \ElggTempFile object and optionaly specify a filename
 *
 * Open the file using the appropriate mode, and you will be able to
 * read and write to the file.
 *
 * Trying to save this entity to the database will fail
 *
 * @see \ElggFile
 * @since 3.0
 */
class ElggTempFile extends ElggFile {

	/**
	 * Set subtype to 'temp_file'.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'temp_file';
		$this->setFilename(uniqid());
	}

	/**
	 * Return the system temp filestore based on the system temp directory.
	 *
	 * @return \ElggTempDiskFilestore
	 */
	protected function getFilestore() {
		return _elgg_services()->temp_filestore;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transfer($owner_guid, $filename = null) {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canDownload($user_guid = 0, $default = false) {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDownloadURL($use_cookie = true, $expires = '+2 hours') {
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInlineURL($use_cookie = false, $expires = '') {
		return '';
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\Filesystem\IOException
	 */
	public function save() : bool {
		throw new IOException("Temp files can't be saved to the database");
	}

}
