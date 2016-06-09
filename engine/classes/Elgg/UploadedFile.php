<?php

namespace Elgg;

/**
 * Uploaded file object
 */
class UploadedFile extends \Symfony\Component\HttpFoundation\File\UploadedFile {

	/**
	 *
	 * @param type $path
	 * @param type $name
	 * @param type $mime_type
	 * @param type $size
	 * @param type $error
	 * @param type $test{@inheritdoc}
	 */
	public function __construct($path, $name, $mime_type, $size, $error, $test = null) {
		if (!isset($test)) {
			$test = defined('PHPUNIT_ELGG_TESTING_APPLICATION');
		}
		parent::__construct($path, $name, $mime_type, $size, $error, $test);
	}

	/**
	 * Saves uploaded file into an ElggFile
	 *
	 * @param \ElggFile $file Elgg file to accept the upload
	 * @return \ElggFile|false
	 */
	public function toElggFile(\ElggFile $file = null) {
		if (!$file) {
			$file = new \ElggFile();
			$file->owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		if ($file->acceptUploadedFile($this)) {
			return $file;
		}
		
		return false;
	}
}
