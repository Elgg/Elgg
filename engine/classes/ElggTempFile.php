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
	 * @return \Elgg\Filesystem\Filestore\TempDiskFilestore
	 */
	protected function getFilestore(): \Elgg\Filesystem\Filestore\TempDiskFilestore {
		return _elgg_services()->temp_filestore;
	}

	/**
	 * {@inheritdoc}
	 */
	public function transfer(int $owner_guid, string $filename = null): bool {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function canDownload(int $user_guid = 0, bool $default = false): bool {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getDownloadURL(bool $use_cookie = true, string $expires = '+2 hours'): ?string {
		return '';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getInlineURL(bool $use_cookie = false, string $expires = ''): ?string {
		return '';
	}
	
	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\Filesystem\IOException
	 */
	public function save(): bool {
		throw new IOException("Temp files can't be saved to the database");
	}
}
