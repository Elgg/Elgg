<?php

/**
 * Service for interacting with uploaded images
 *
 * @access private
 */
class CKEditorUploadService {
	protected $errorMessage = '';
	protected $fileInfo = array();
	protected $user = null;
	protected $assetDirectory = '';
	protected $assetFilename = '';
	protected $assetFormat = '';
	protected $resizer;
	protected $rootDir;
	protected $dirLocator;
	protected $userGuid;
	protected $uploadObject = null;

	protected $bytesPerPixel = 4;
	protected $fudgeFactor = 1.4;

	const ASSET_DIR = 'assets/images';

	/**
	 * Create the upload service
	 *
	 * @param string               $rootDir Root directory for files. Usually the data directory.
	 * @param int                  $guid    GUID of the owner of the upload
	 * @param CKEditorImageResizer $resizer Required for storing an image, but not retrieving
	 * @throws InvalidArgumentException
	 */
	public function __construct($rootDir, $guid, $resizer = null) {
		if ((int)$guid <= 0) {
			throw new InvalidArgumentException("A GUID of $guid is invalid");
		}

		$this->rootDir = rtrim($rootDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->userGuid = $guid;
		$this->dirLocator = new Elgg_EntityDirLocator($guid);
		$this->resizer = $resizer;
	}

	/**
	 * Store the uploaded file as an asset for the user. Returns the URL for
	 * the asset
	 *
	 * @param array $fileInfo A slice of the $_FILES array for an uploaded file
	 * @return string
	 */
	public function store(array $fileInfo) {
		$this->fileInfo = $fileInfo;

		if (!$this->validateUpload()) {
			return '';
		}

		if (!$this->prepareForAssetStorage()) {
			return '';
		}

		$uploadFilepath = $this->getUploadFilepath();
		$assetFilepath = $this->getAssetFilepath();
		$assetFormat = $this->getAssetFormat();

		if (!$this->resizer->process($uploadFilepath, $assetFilepath, $assetFormat)) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:resize'));
			return '';
		}

		$this->createUploadObject();

		return $this->getAssetURL();
	}

	/**
	 * Retrieve the file path of an asset
	 *
	 * @param type $filename
	 * @return string
	 */
	public function retrieve($filename) {
		$this->fileInfo = array();
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
	 * Get the last error message
	 * @return string
	 */
	public function getErrorMessage() {
		return $this->errorMessage;
	}

	/**
	 * Set the error message
	 * @param string $message
	 * @return void
	 */
	protected function setErrorMessage($message) {
		$this->errorMessage = $message;
	}

	/**
	 * Validate the upload for size and errors
	 * @return bool
	 */
	protected function validateUpload() {
		// some general upload error
		if ($this->fileInfo['error'] != 0) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:upload'));
			return false;
		}

		// make sure an uploaded file
		if (!is_uploaded_file($this->fileInfo['tmp_name'])) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:upload'));
			return false;
		}

		// if too large, reject
		$imgInfo = getimagesize($this->fileInfo['tmp_name']);
		$numPixels = $imgInfo[0] * $imgInfo[1];
		$memAvail = ini_get('memory_limit');
		$memAvail = rtrim($memAvail, 'M');
		$memAvail = $memAvail * 1024 * 1024;
		$memUsed = memory_get_usage();
		$memRequired = ceil($this->fudgeFactor * $this->bytesPerPixel * $numPixels);
		if (($memRequired + $memUsed) > $memAvail) {
			$this->setErrorMessage(elgg_echo('ckeditor:failure:too_big'));
			return false;
		}

		return true;
	}

	/**
	 * Prepare for saving the asset
	 * @return bool
	 */
	protected function prepareForAssetStorage() {
		$this->setAssetDirectory();
		$this->setAssetFormat();
		$this->setAssetFilename();
		return $this->createAssetDirectory();
	}

	/**
	 * Set the user's asset directory location
	 * @return void
	 */
	protected function setAssetDirectory() {
		$this->assetDirectory = $this->rootDir . $this->dirLocator->getPath() . self::ASSET_DIR;
	}

	/**
	 * Set the filename for the asset
	 * @param string $filename If not set, determines filename from original file
	 * @return void
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
	 * 
	 * @warning Used 'jpeg' and 'png' because it matches content type and GD functions.
	 * 
	 * @return void
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
		if (!$this->uploadObject) {
			return '';
		}

		$objectGuid = $this->uploadObject->guid;
		$url = "uploads/images/$this->userGuid/$objectGuid/$this->assetFilename";

		return elgg_normalize_url($url);
	}

	/**
	 * Create the user's asset directoryu
	 * @return bool
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

	/**
	 * Create the upload object to have reference to the file
	 * @return void
	 */
	protected function createUploadObject() {
		$this->uploadObject = new CKEditorUpload();
		$this->uploadObject->owner_guid = $this->userGuid;
		$this->uploadObject->filePath = self::ASSET_DIR . '/' . $this->assetFilename;
		$this->uploadObject->save();
	}
}
