<?php

/**
 * This class represents a physical file.
 *
 * Create a new \ElggFile object and specify a filename, and optionally a
 * FileStore (if one isn't specified then the default is assumed.)
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
 */
class ElggFile extends \ElggObject {
	/** Filestore */
	private $filestore;

	/** File handle used to identify this file in a filestore. Created by open. */
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
	 * Loads an \ElggFile entity.
	 *
	 * @param \stdClass $row Database result or null for new \ElggFile
	 */
	public function __construct($row = null) {
		parent::__construct($row);

		// Set default filestore
		$this->filestore = $this->getFilestore();
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
		return $this->filestore->getFilenameOnFilestore($this);
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
		$fs = $this->getFilestore();
		// @todo add getSize() to \ElggFilestore
		return $fs->getSize($prefix, $container_guid);
	}

	/**
	 * Get the mime type of the file.
	 *
	 * @return string
	 */
	public function getMimeType() {
		if ($this->mimetype) {
			return $this->mimetype;
		}

		// @todo Guess mimetype if not here
	}

	/**
	 * Set the mime type of the file.
	 *
	 * @param string $mimetype The mimetype
	 *
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
		if (!$file && isset($this)) {
			$file = $this->getFilenameOnFilestore();
		}

		if (!is_readable($file)) {
			return false;
		}

		$mime = $default;

		// for PHP5 folks.
		if (function_exists('finfo_file') && defined('FILEINFO_MIME_TYPE')) {
			$resource = finfo_open(FILEINFO_MIME_TYPE);
			if ($resource) {
				$mime = finfo_file($resource, $file);
			}
		}

		// for everyone else.
		if (!$mime && function_exists('mime_content_type')) {
			$mime = mime_content_type($file);
		}

		$original_filename = isset($this) ? $this->originalfilename : basename($file);
		$params = array(
			'filename' => $file,
			'original_filename' => $original_filename, // @see file upload action
			'default' => $default,
		);
		return _elgg_services()->hooks->trigger('mime_type', 'file', $params, $mime);
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
	 * @throws IOException|InvalidParameterException
	 */
	public function open($mode) {
		if (!$this->getFilename()) {
			throw new \IOException("You must specify a name before opening a file.");
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
			throw new \InvalidParameterException($msg);
		}

		// Get the filestore
		$fs = $this->getFilestore();

		// Ensure that we save the file details to object store
		//$this->save();

		// Open the file handle
		$this->handle = $fs->open($this, $mode);

		return $this->handle;
	}

	/**
	 * Write data.
	 *
	 * @param string $data The data
	 *
	 * @return bool
	 */
	public function write($data) {
		$fs = $this->getFilestore();

		return $fs->write($this->handle, $data);
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
		$fs = $this->getFilestore();

		return $fs->read($this->handle, $length, $offset);
	}

	/**
	 * Gets the full contents of this file.
	 *
	 * @return mixed The file contents.
	 */
	public function grabFile() {
		$fs = $this->getFilestore();
		return $fs->grabFile($this);
	}

	/**
	 * Close the file and commit changes
	 *
	 * @return bool
	 */
	public function close() {
		$fs = $this->getFilestore();

		if ($fs->close($this->handle)) {
			$this->handle = null;

			return true;
		}

		return false;
	}

	/**
	 * Delete this file.
	 *
	 * @return bool
	 */
	public function delete() {
		$fs = $this->getFilestore();
		
		$result = $fs->delete($this);
		
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
	 * @return bool
	 */
	public function seek($position) {
		$fs = $this->getFilestore();

		// @todo add seek() to \ElggFilestore
		return $fs->seek($this->handle, $position);
	}

	/**
	 * Return the current position of the file.
	 *
	 * @return int The file position
	 */
	public function tell() {
		$fs = $this->getFilestore();

		return $fs->tell($this->handle);
	}

	/**
	 * Return the size of the file in bytes.
	 *
	 * @return int
	 * @since 1.9
	 */
	public function getSize() {
		return $this->filestore->getFileSize($this);
	}

	/**
	 * Return the size of the file in bytes.
	 *
	 * @return int
	 * @deprecated 1.8 Use getSize()
	 */
	public function size() {
		elgg_deprecated_notice("Use \ElggFile::getSize() instead of \ElggFile::size()", 1.9);
		return $this->getSize();
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 *
	 * @return bool
	 */
	public function eof() {
		$fs = $this->getFilestore();

		return $fs->eof($this->handle);
	}

	/**
	 * Returns if the file exists
	 *
	 * @return bool
	 */
	public function exists() {
		$fs = $this->getFilestore();

		return $fs->exists($this);
	}

	/**
	 * Set a filestore.
	 *
	 * @param \ElggFilestore $filestore The file store.
	 *
	 * @return void
	 */
	public function setFilestore(\ElggFilestore $filestore) {
		$this->filestore = $filestore;
	}

	/**
	 * Return a filestore suitable for saving this file.
	 * This filestore is either a pre-registered filestore,
	 * a filestore as recorded in metadata or the system default.
	 *
	 * @return \ElggFilestore
	 *
	 * @throws ClassNotFoundException
	 */
	protected function getFilestore() {
		// Short circuit if already set.
		if ($this->filestore) {
			return $this->filestore;
		}

		// ask for entity specific filestore
		// saved as filestore::className in metadata.
		// need to get all filestore::* metadata because the rest are "parameters" that
		// get passed to filestore::setParameters()
		if ($this->guid) {
			$options = array(
				'guid' => $this->guid,
				'where' => array("n.string LIKE 'filestore::%'"),
			);

			$mds = elgg_get_metadata($options);

			$parameters = array();
			foreach ($mds as $md) {
				list( , $name) = explode("::", $md->name);
				if ($name == 'filestore') {
					$filestore = $md->value;
				}
				$parameters[$name] = $md->value;
			}
		}

		// need to check if filestore is set because this entity is loaded in save()
		// before the filestore metadata is saved.
		if (isset($filestore)) {
			if (!class_exists($filestore)) {
				$msg = "Unable to load filestore class " . $filestore . " for file " . $this->guid;
				throw new \ClassNotFoundException($msg);
			}

			$this->filestore = new $filestore();
			$this->filestore->setParameters($parameters);
			// @todo explain why $parameters will always be set here (PhpStorm complains)
		}

		// this means the entity hasn't been saved so fallback to default
		if (!$this->filestore) {
			$this->filestore = get_default_filestore();
		}

		return $this->filestore;
	}

	/**
	 * Save the file
	 *
	 * Write the file's data to the filestore and save
	 * the corresponding entity.
	 *
	 * @see \ElggObject::save()
	 *
	 * @return bool
	 */
	public function save() {
		if (!parent::save()) {
			return false;
		}

		// Save datastore metadata
		$params = $this->filestore->getParameters();
		foreach ($params as $k => $v) {
			$this->setMetadata("filestore::$k", $v);
		}

		// Now make a note of the filestore class
		$this->setMetadata("filestore::filestore", get_class($this->filestore));

		return true;
	}
}
