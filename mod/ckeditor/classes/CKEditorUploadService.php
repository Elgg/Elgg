<?php

/**
 * Service for moving uploaded images into the user's assets
 * 
 */
class CKEditorUploadService {
	protected $errorMessage = '';
	protected $maximumDimension = 200;
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
	 * @param array    $fileInfo A slice of the $_FILES array for an uploaded file
	 * @param ElggUser $user     The user who uploaded the photo
	 * @return string 
	 */
	public function process(array $fileInfo, ElggUser $user) {
		$this->fileInfo = $fileInfo;
		$this->user = $user;
		
		if (!$this->validateUpload()) {
			return '';
		}

		if (!$this->createAssetDirectory()) {
			return '';
		}

		$uploadFilename = $this->getUploadFilename();
		$assetFilename = $this->getAssetFilename();
		$assetFormat = $this->getAssetFormat();
		error_log($assetFilename);

		$resizer = new CKEditorImageResizer($this->maximumDimension);
		if (!$resizer->process($uploadFilename, $assetFilename, $assetFormat)) {
			$this->errorMessage = elgg_echo('ckeditor:failure:resize');
			return '';
		}

		return $this->getAssetURL();
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
	 * Validate the upload for size and errors
	 * @return boolean 
	 */
	protected function validateUpload() {
		// some general upload error
		if ($this->fileInfo['error'] != 0) {
			$this->errorMessage = elgg_echo('ckeditor:failure:upload');
			return false;
		}

		// if too large, reject
		
		return true;
	}

	/**
	 * Get the temporary filename for the upload
	 * @return string
	 */
	protected function getUploadFilename() {
		return $this->fileInfo['tmp_name'];
	}

	/**
	 * Get the filename for the asset
	 * @return string 
	 */
	protected function getAssetFilename() {
		$name = pathinfo($this->getOriginalFilename(), PATHINFO_FILENAME);
		$name = preg_replace('/\W/', '', $name);
		$name = $name . time() . '.' . $this->getAssetFormat();
		$this->assetFilename = $name;
		return "$this->assetDirectory/$name";
	}

	/**
	 * Get the original name of the file the user uploaded
	 * @return string 
	 */
	protected function getOriginalFilename() {
		return $this->fileInfo['name'];
	}

	/**
	 * Determine the format of the asset based on upload image
	 * @return string 
	 */
	protected function getAssetFormat() {
		$filename = $this->getOriginalFilename();
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		switch (strtolower($ext)) {
			case 'png':
			case 'gif':
			case 'bmp':
				$format = 'png';
				break;
			case 'jpeg':
			case 'jpg':
			default:
				$format = 'jpeg';
				break;
		}
		return $format;
	}

	protected function getAssetURL() {
		$user_guid = $this->user->guid;
		$url = "uploads/images/$user_guid/$this->assetFilename";
		return elgg_normalize_url($url);
	}

	protected function createAssetDirectory() {
		// @todo - another way to get user's data dir?
		$file = new ElggFile();
		$file->owner_guid = $this->user->guid;
		$file->setFilename(self::ASSET_DIR);
		$directory = $file->getFilenameOnFilestore();
		$this->assetDirectory = $directory;
		if (!file_exists($directory)) {
			return mkdir($directory, 0700, true);
		} else {
			return true;
		}
	}
}
