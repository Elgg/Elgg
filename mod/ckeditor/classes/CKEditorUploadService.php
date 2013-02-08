<?php

/**
 * Service for interacting with uploaded images
 *
 */
class CKEditorUploadService {
	protected $errorMessage = '';
	protected $maximumDimension = 700;
	protected $fileInfo = array();
	protected $user = null;
	protected $assetDirectory = '';
	protected $assetFilename = '';
	protected $assetFormat = '';

	const ASSET_DIR = 'assets/images';

	/**
	 * Store the uploaded file as an asset for the user. Returns the URL for
	 * the asset
	 *
	 * @param ElggUser $user     The user who uploaded the photo
	 * @param array    $fileInfo A slice of the $_FILES array for an uploaded file
	 * @return string
	 */
	public function store(ElggUser $user, array $fileInfo) {
		$this->fileInfo = $fileInfo;
		$this->user = $user;

		if (!$this->validateUpload()) {
			return '';
		}

		if (!$this->prepareForAssetStorage()) {
			return '';
		}

		$uploadFilepath = $this->getUploadFilepath();
		$assetFilepath = $this->getAssetFilepath();
		$assetFormat = $this->getAssetFormat();

		$resizer = new CKEditorImageResizer($this->maximumDimension);
		if (!$resizer->process($uploadFilepath, $assetFilepath, $assetFormat)) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:resize'));
			return '';
		}

		return $this->getAssetURL();
	}

	/**
	 * Retrieve the file path of an asset
	 *
	 * @param ElggUser $user
	 * @param type $filename
	 * @return string
	 */
	public function retrieve(ElggUser $user, $filename) {
		$this->fileInfo = array();
		$this->user = $user;
		$this->setAssetFilename($filename);
		$this->setAssetDirectory();
		$filepath = $this->getAssetFilepath();
		if (file_exists($filepath)) {
			return $filepath;
		} else {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:missing'));
			return '';
		}
	}

	/**
	 * Sets the size of the largest dimension (width and height)
	 * @param int $size Max of width and height in pixels
	 * @return void
	 */
	public function setMaximumDimension($size) {
		$this->maximumDimension = $size;
	}

	/**
	 * Get the last error message
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * Set the error message
	 * @param string $message
	 */
	protected function setErrorMessage($message) {
		$this->errorMessage = $message;
	}

	/**
	 * Validate the upload for size and errors
	 * @return boolean
	 */
	protected function validateUpload() {
		// some general upload error
		if ($this->fileInfo['error'] != 0) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:upload'));
			return false;
		}

		// if too large, reject

		return true;
	}

	/**
	 * Prepare for saving the asset
	 * @return boolean
	 */
	protected function prepareForAssetStorage() {
		$this->setAssetDirectory();
		$this->setAssetFormat();
		$this->setAssetFilename();
		return $this->createAssetDirectory();
	}

	/**
	 * Set the user's asset directory location
	 */
	protected function setAssetDirectory() {
		// @todo - another way to get user's data dir?
		$file = new ElggFile();
		$file->owner_guid = $this->user->guid;
		$file->setFilename(self::ASSET_DIR);
		$directory = $file->getFilenameOnFilestore();
		$this->assetDirectory = $directory;
	}

	/**
	 * Set the filename for the asset
	 * @param string $filename If not set, determines filename from original file
	 */
	protected function setAssetFilename($filename = '') {
		if ($filename) {
			$this->assetFilename = $filename;
		} else {
			$name = pathinfo($this->getOriginalFilename(), PATHINFO_FILENAME);
			$name = preg_replace('/\W/', '', $name);
			$name = $name . time() . '.' . $this->getAssetFormat();
			$this->assetFilename = $name;
		}
	}

	/**
	 * Determine the format of the asset based on upload image
	 * @warning Use 'jpeg' and 'png' because it matches content type and GD functions.
	 */
	protected function setAssetFormat() {
		$filename = $this->getOriginalFilename();
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		switch (strtolower($ext)) {
			case 'png':
			case 'gif':
			case 'bmp':
				$this->assetFormat = 'png';
				break;
			case 'jpeg':
			case 'jpg':
			default:
				$this->assetFormat = 'jpeg';
				break;
		}
	}

	/**
	 * Get the temporary file path for the upload
	 * @return string
	 */
	protected function getUploadFilepath() {
		return $this->fileInfo['tmp_name'];
	}

	/**
	 * Get the original name of the file the user uploaded
	 * @return string
	 */
	protected function getOriginalFilename() {
		return $this->fileInfo['name'];
	}

	/**
	 * Get the full path for the asset
	 * @return string
	 */
	protected function getAssetFilepath() {
		return "$this->assetDirectory/$this->assetFilename";
	}

	/**
	 * Get the format of this asset
	 * @return string
	 */
	protected function getAssetFormat() {
		return $this->assetFormat;
	}

	/**
	 * Get the asset's URL
	 * @return string
	 */
	protected function getAssetURL() {
		$user_guid = $this->user->guid;
		$url = "uploads/images/$user_guid/$this->assetFilename";
		return elgg_normalize_url($url);
	}

	/**
	 * Create the user's asset directoryu
	 * @return boolean
	 */
	protected function createAssetDirectory() {
		$result = true;
		if (!file_exists($this->assetDirectory)) {
			$result = mkdir($this->assetDirectory, 0700, true);
			if (!$result) {
				$this->setErrorMessage(elgg_echo('ckeditor:failure:permissions'));
			}
		}
		return $result;
	}
}
