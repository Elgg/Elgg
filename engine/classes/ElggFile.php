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
	/**
	 *
	 * @var stream The stream context created by open
	 */
	private $stream;

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
	 * @deprecated 1.11 Use getDataStorage() to interact with the physical file.
	 *
	 * @return string
	 */
	public function getFilenameOnFilestore() {
		$ds = $this->getDataStorage();
		$filename = $this->getFilename();
		if (!$filename) {
			throw new \Elgg\Di\MissingValueException("Filename must be set to get the full file path");
		}
		return $ds->fullPath($filename);
	}

	/**
	 * Return the size of the filestore associated with this file
	 * 
	 * @todo @deprecated 1.11 Use getDataStorageSize() (don't like this name. Or this method.)
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
	 * @param mixed $file    The full path of the file to check. For uploaded files, use tmp_name.
	 * @param mixed $default A default. Useful to pass what the browser thinks it is.
	 * @since 1.7.12
	 *
	 * @return mixed Detected type on success, false on failure.
	 * @todo Move this out into a utility class
	 */
	public function detectMimeType($file = null, $default = null) {
		if (!$file) {
			if (isset($this) && $this->filename) {
				$file = $this->filename;
			} else {
				return false;
			}
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

		$params = array(
			'filename' => $file,
			'original_filename' => $file->originalfilename, // @see file upload action
			'default' => $default,
		);
		return _elgg_services()->hooks->trigger('mime_type', 'file', $params, $mime);
	}

	/**
	 * Set the optional file description.
	 *
	 * @param string $description The description.
	 * 
	 * @todo Why is this here and not on ElggEntity?
	 *
	 * @return bool
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Open the file with the given mode. Creates if does not exist.
	 *
	 * @param string $mode read|write|append
	 *
	 * @return resource Context stream
	 *
	 * @throws IOException|InvalidParameterException
	 */
	public function open($mode) {
		if (!$this->getFilename()) {
			throw new \IOException("You must specify a name before opening a file.");
		}

		// Sanity check
		if (!in_array($mode, ['read', 'write', 'append'])) {
			$msg = "Unrecognized file mode '" . $mode . "'";
			throw new \InvalidParameterException($msg);
		}
		
		return $this->stream = $this->getDataStorage()
				->file($this->getFilename())
				->createStream();
	}

	/**
	 * Write data.
	 *
	 * @param string $data The data
	 *
	 * @return bool
	 */
	public function write($data) {
		return $this->getDataStorage()
				->file($this->getFilename())
				->setContent($data);
	}
	
	/**
	 * Read data.
	 *
	 * @param int $length Bytes to read.
	 * @param int $offset The offset in bytes to start from.
	 *
	 * @return mixed Data or false
	 */
	public function read($length, $offset = 0) {
		$stream = $this->getStream();
		$stream->seek($offset);
		return $stream->read($length);
	}
	
	/**
	 * Gets the full contents of this file.
	 *
	 * @return mixed The file contents.
	 */
	public function grabFile() {
		return $this->getDataStorage()->file($this->getFilename())->getContent();
	}
	
	/**
	 * Close the file
	 *
	 * @deprecated since version 1.11 ElggFile::close is no longer needed.
	 * @return bool
	 */
	public function close() {
		elgg_deprecated_notice("ElggFile::close is no longer needed.", 1.11);
		return true;
	}

	/**
	 * Delete this file.
	 *
	 * @return bool
	 */
	public function delete() {
		$filename = $this->getFilename();
		$result = true;
		
		if ($this->getGUID()) {
			$result = parent::delete();
		}
		
		if ($result) {
			$result &= $this->getDataStorage()->file($filename)->delete();
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

		return $fs->seek($this->handle, $position);
	}

	/**
	 * Return the current position of the file.
	 *
	 * @return int The file position
	 */
	public function tell() {
		return $this->getStream()->tell();
	}

	/**
	 * Return the size of the file in bytes.
	 *
	 * @return int
	 * @since 1.9
	 */
	public function getSize() {
		return $this->getDataStorage()->file($this->getFilename())->getSize();
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 *
	 * @return bool
	 */
	public function eof() {
		return $this->getStream()->eof();
	}

	/**
	 * Returns if the file exists
	 *
	 * @return bool
	 */
	public function exists() {
		return $this->getDataStorage()->file($this->getFilename())->exists();
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
	 * Get the data storage for this entity
	 * 
	 * @return \Elgg\Filesystem\Adapter\Adapter
	 */
	public function getDataStorage() {
		$storage = _elgg_services()->dataStorage;
		
		// set directory for this object
		if ($this->owner_guid) {
			$path = $storage->getPathPrefix($this->owner_guid);
			$storage = $storage->directory($path);
		}
		
		return $storage;
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

			try {
				$this->filestore = new $filestore();
				// @todo Why isn't this just in the constructor?
				$this->filestore->setParameters($parameters);
				// How is $parameters always set here? (PhpStorm complains)
				// $parameters is set before $filestore. If $filestore is set, $parameters is too.
			} catch (ClassNotFoundException $e) {
				// there's a problem with the filestore
				$msg = "Unable to load filestore  " . $filestore . " for file " . $this->guid;
				$msg .= $e->getMessage();
				throw new \ClassNotFoundException($msg);
			}
			
		}

		// this means the entity hasn't been saved so fallback to default
		if (!$this->filestore) {
			$this->filestore = get_default_filestore();
		}

		return $this->filestore;
	}
	
	/**
	 * Get the stream for the currently opened file.
	 * 
	 * @return resource Stream
	 * @throws IOException
	 */
	private function getStream() {
		if (!$this->stream) {
			throw new IOException("Files must be opened before calling getStream().");
		}
		
		return $this->stream;
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
		// @todo delete file on fail?
		return parent::save();
	}
	
	public function moveUploadedFile($from, $to) {
		return $this->filestore->moveUploadedFile($from, $to);
	}
}
