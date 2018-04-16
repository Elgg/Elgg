<?php

use Elgg\Filesystem\MimeTypeDetector;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * This class represents a physical file.
 *
 * Create a new \ElggFile object and specify a filename
 *
 * Open the file using the appropriate mode, and you will be able to
 * read and write to the file.
 *
 * Optionally, you can also call the file's save() method, this will
 * turn the file into an entity in the system and permit you to do
 * things like attach tags to the file. If you do not save the file, no
 * entity is created in the database. This is because there are occasions
 * when you may want access to file data on datastores using the \ElggFile
 * interface without a need to persist information such as temporary files.
 *
 * @package    Elgg.Core
 * @subpackage DataModel.File
 *
 * @property string $mimetype         MIME type of the file
 * @property string $simpletype       Category of the file
 * @property string $originalfilename Filename of the original upload
 * @property int    $upload_time      Timestamp of the upload action, used as a filename prefix
 * @property string $filestore_prefix Prefix (directory) on user's filestore where the file is saved
 */
class ElggFile extends ElggObject {

	/**
	 * @var resource|null File handle used to identify this file in a filestore. Created by open.
	 */
	private $handle;

	/**
	 * Set subtype to 'file'.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "file";
	}

	/**
	 * Set the filename of this file.
	 *
	 * @param string $name The filename.
	 *
	 * @return void
	 */
	public function setFilename($name) {
		$this->filename = $name;
	}

	/**
	 * Return the filename.
	 *
	 * @return string
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Return the filename of this file as it is/will be stored on the
	 * filestore, which may be different to the filename.
	 *
	 * @return string
	 */
	public function getFilenameOnFilestore() {
		return $this->getFilestore()->getFilenameOnFilestore($this);
	}

	/**
	 * Return the size of the filestore associated with this file
	 *
	 * @param string $prefix         Storage prefix
	 * @param int    $container_guid The container GUID of the checked filestore
	 *
	 * @return int
	 */
	public function getFilestoreSize($prefix = '', $container_guid = 0) {
		if (!$container_guid) {
			$container_guid = $this->container_guid;
		}
		// @todo add getSize() to \ElggFilestore
		return $this->getFilestore()->getSize($prefix, $container_guid);
	}

	/**
	 * Get the mime type of the file.
	 * Returns mimetype metadata value if set, otherwise attempts to detect it.
	 * @return string
	 */
	public function getMimeType() {
		if ($this->mimetype) {
			return $this->mimetype;
		}
		return $this->detectMimeType();
	}

	/**
	 * Set the mime type of the file.
	 *
	 * @param string $mimetype The mimetype
	 * @return bool
	 */
	public function setMimeType($mimetype) {
		return $this->mimetype = $mimetype;
	}

	/**
	 * Detects mime types based on filename or actual file.
	 *
	 * @note This method can be called both dynamically and statically
	 *
	 * @param mixed $file    The full path of the file to check. For uploaded files, use tmp_name.
	 * @param mixed $default A default. Useful to pass what the browser thinks it is.
	 * @since 1.7.12
	 *
	 * @return mixed Detected type on success, false on failure.
	 * @todo Move this out into a utility class
	 */
	public function detectMimeType($file = null, $default = null) {
		$class = __CLASS__;
		if (!$file && isset($this) && $this instanceof $class) {
			$file = $this->getFilenameOnFilestore();
		}

		if (!is_readable($file)) {
			return false;
		}

		$mime = $default;

		$detected = (new MimeTypeDetector())->tryStrategies($file);
		if ($detected) {
			$mime = $detected;
		}

		$original_filename = isset($this) && $this instanceof $class ? $this->originalfilename : basename($file);
		$params = [
			'filename' => $file,
			'original_filename' => $original_filename, // @see file upload action
			'default' => $default,
		];
		return _elgg_services()->hooks->trigger('mime_type', 'file', $params, $mime);
	}

	/**
	 * Get the simple type of the file.
	 * Returns simpletype metadata value if set, otherwise parses it from mimetype
	 * @see elgg_get_file_simple_type()
	 *
	 * @return string 'document', 'audio', 'video', or 'general' if the MIME type was unrecognized
	 */
	public function getSimpleType() {
		if (isset($this->simpletype)) {
			return $this->simpletype;
		}
		$mime_type = $this->getMimeType();
		return elgg_get_file_simple_type($mime_type);
	}

	/**
	 * Set the optional file description.
	 *
	 * @param string $description The description.
	 *
	 * @return bool
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Open the file with the given mode
	 *
	 * @param string $mode Either read/write/append
	 *
	 * @return resource File handler
	 *
	 * @throws IOException
	 * @throws InvalidParameterException
	 */
	public function open($mode) {
		if (!$this->getFilename()) {
			throw new IOException("You must specify a name before opening a file.");
		}

		// See if file has already been saved
		// seek on datastore, parameters and name?
		// Sanity check
		if (
				($mode != "read") &&
				($mode != "write") &&
				($mode != "append")
		) {
			$msg = "Unrecognized file mode '" . $mode . "'";
			throw new InvalidParameterException($msg);
		}

		// Open the file handle
		$this->handle = $this->getFilestore()->open($this, $mode);

		return $this->handle;
	}

	/**
	 * Write data.
	 *
	 * @param string $data The data
	 *
	 * @return false|int
	 */
	public function write($data) {
		return $this->getFilestore()->write($this->handle, $data);
	}

	/**
	 * Read data.
	 *
	 * @param int $length Amount to read.
	 * @param int $offset The offset to start from.
	 *
	 * @return mixed Data or false
	 */
	public function read($length, $offset = 0) {
		return $this->getFilestore()->read($this->handle, $length, $offset);
	}

	/**
	 * Gets the full contents of this file.
	 *
	 * @return mixed The file contents.
	 */
	public function grabFile() {
		return $this->getFilestore()->grabFile($this);
	}

	/**
	 * Close the file and commit changes
	 *
	 * @return bool
	 */
	public function close() {
		if ($this->getFilestore()->close($this->handle)) {
			$this->handle = null;

			return true;
		}

		return false;
	}

	/**
	 * Delete this file.
	 *
	 * @param bool $follow_symlinks If true, will also delete the target file if the current file is a symlink
	 * @return bool
	 */
	public function delete($follow_symlinks = true) {
		$result = $this->getFilestore()->delete($this, $follow_symlinks);

		if ($this->getGUID() && $result) {
			$result = parent::delete();
		}

		return $result;
	}

	/**
	 * Seek a position in the file.
	 *
	 * @param int $position Position in bytes
	 *
	 * @return void
	 */
	public function seek($position) {
		// @todo add seek() to \ElggFilestore
		$this->getFilestore()->seek($this->handle, $position);
	}

	/**
	 * Return the current position of the file.
	 *
	 * @return int The file position
	 */
	public function tell() {
		return $this->getFilestore()->tell($this->handle);
	}

	/**
	 * Updates modification time of the file and clears stats cache for the file
	 * @return bool
	 */
	public function setModifiedTime() {
		$filestorename = $this->getFilenameOnFilestore();
		$modified = touch($filestorename);
		if ($modified) {
			clearstatcache(true, $filestorename);
		} else {
			elgg_log("Unable to update modified time for $filestorename", 'ERROR');
		}
		return $modified;
	}

	/**
	 * Returns file modification time
	 * @return int
	 */
	public function getModifiedTime() {
		return filemtime($this->getFilenameOnFilestore());
	}

	/**
	 * Return the size of the file in bytes.
	 *
	 * @return int
	 * @since 1.9
	 */
	public function getSize() {
		return $this->getFilestore()->getFileSize($this);
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 *
	 * @return bool
	 */
	public function eof() {
		return $this->getFilestore()->eof($this->handle);
	}

	/**
	 * Returns if the file exists
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->getFilestore()->exists($this);
	}

	/**
	 * Return the system filestore based on dataroot.
	 *
	 * @return \ElggDiskFilestore
	 */
	protected function getFilestore() {
		return _elgg_services()->filestore;
	}

	/**
	 * Transfer a file to a new owner and sets a new filename,
	 * copies file contents to a new location.
	 *
	 * This is an alternative to using rename() which fails to move files to
	 * a non-existent directory under new owner's filestore directory
	 *
	 * @param int    $owner_guid New owner's guid
	 * @param string $filename   New filename (uses old filename if not set)
	 * @return bool
	 */
	public function transfer($owner_guid, $filename = null) {
		if (!$owner_guid) {
			return false;
		}

		if (!$this->exists()) {
			return false;
		}

		if (!$filename) {
			$filename = $this->getFilename();
		}
		$filestorename = $this->getFilenameOnFilestore();

		$this->owner_guid = $owner_guid;
		$this->setFilename($filename);
		$this->open('write');
		$this->close();

		return rename($filestorename, $this->getFilenameOnFilestore());
	}

	/**
	 * Writes contents of the uploaded file to an instance of ElggFile
	 *
	 * @note Note that this function moves the file and populates properties,
	 * but does not call ElggFile::save().
	 *
	 * @note This method will automatically assign a filename on filestore based
	 * on the upload time and filename. By default, the file will be written
	 * to /file directory on owner's filestore. You can change this directory,
	 * by setting 'filestore_prefix' property of the ElggFile instance before
	 * calling this method.
	 *
	 * @param UploadedFile $upload Uploaded file object
	 * @return bool
	 */
	public function acceptUploadedFile(UploadedFile $upload) {
		if (!$upload->isValid()) {
			return false;
		}

		$old_filestorename = '';
		if ($this->exists()) {
			$old_filestorename = $this->getFilenameOnFilestore();
		}

		$originalfilename = $upload->getClientOriginalName();
		$this->originalfilename = $originalfilename;
		if (empty($this->title)) {
			$this->title = htmlspecialchars($this->originalfilename, ENT_QUOTES, 'UTF-8');
		}

		$this->upload_time = time();
		$prefix = $this->filestore_prefix ?: 'file';
		$prefix = trim($prefix, '/');
		$filename = elgg_strtolower("$prefix/{$this->upload_time}{$this->originalfilename}");
		$this->setFilename($filename);
		$this->filestore_prefix = $prefix;

		$hook_params = [
			'file' => $this,
			'upload' => $upload,
		];

		$uploaded = _elgg_services()->hooks->trigger('upload', 'file', $hook_params);
		if ($uploaded !== true && $uploaded !== false) {
			$filestorename = $this->getFilenameOnFilestore();
			try {
				$uploaded = $upload->move(pathinfo($filestorename, PATHINFO_DIRNAME), pathinfo($filestorename, PATHINFO_BASENAME));
			} catch (FileException $ex) {
				_elgg_services()->logger->error($ex->getMessage());
				$uploaded = false;
			}
		}

		if ($uploaded) {
			if ($old_filestorename && $old_filestorename != $this->getFilenameOnFilestore()) {
				// remove old file
				unlink($old_filestorename);
			}
			$mime_type = $this->detectMimeType(null, $upload->getClientMimeType());
			$this->setMimeType($mime_type);
			$this->simpletype = elgg_get_file_simple_type($mime_type);
			_elgg_services()->events->triggerAfter('upload', 'file', $this);
			return true;
		}

		return false;
	}

	/**
	 * Get property names to serialize.
	 *
	 * @return string[]
	 */
	public function __sleep() {
		return array_diff(array_keys(get_object_vars($this)), [
			// a resource
			'handle',
		]);
	}

	/**
	 * Checks the download permissions for the file
	 *
	 * @param int  $user_guid GUID of the user (defaults to logged in user)
	 * @param bool $default   Default permission
	 *
	 * @return bool
	 */
	public function canDownload($user_guid = 0, $default = true) {
		return _elgg_services()->userCapabilities->canDownload($this, $user_guid, $default);
	}

	/**
	 * Returns file's download URL
	 *
	 * @note This does not work for files with custom filestores.
	 *
	 * @param bool   $use_cookie Limit URL validity to current session only
	 * @param string $expires    URL expiration, as a string suitable for strtotime()
	 *
	 * @return string
	 */
	public function getDownloadURL($use_cookie = true, $expires = '+2 hours') {

		$file_svc = new \Elgg\FileService\File();
		$file_svc->setFile($this);
		$file_svc->setExpires($expires);
		$file_svc->setDisposition('attachment');
		$file_svc->bindSession($use_cookie);
		$url = $file_svc->getURL();

		$params = [
			'entity' => $this,
		];

		return _elgg_services()->hooks->trigger('download:url', 'file', $params, $url);
	}

	/**
	 * Returns file's URL for inline display
	 * Suitable for displaying cacheable resources, such as user avatars
	 *
	 * @note This does not work for files with custom filestores.
	 *
	 * @param bool   $use_cookie Limit URL validity to current session only
	 * @param string $expires    URL expiration, as a string suitable for strtotime()
	 *
	 * @return string
	 */
	public function getInlineURL($use_cookie = false, $expires = '') {
		$file_svc = new \Elgg\FileService\File();
		$file_svc->setFile($this);
		if ($expires) {
			$file_svc->setExpires($expires);
		}
		$file_svc->setDisposition('inline');
		$file_svc->bindSession($use_cookie);
		$url = $file_svc->getURL();

		$params = [
			'entity' => $this,
		];

		return _elgg_services()->hooks->trigger('inline:url', 'file', $params, $url);
	}

}
