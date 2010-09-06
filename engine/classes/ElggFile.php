<?php

/**
 * @class ElggFile
 * This class represents a physical file.
 *
 * Usage:
 *		Create a new ElggFile object and specify a filename, and optionally a FileStore (if one isn't specified
 *		then the default is assumed.
 *
 * 		Open the file using the appropriate mode, and you will be able to read and write to the file.
 *
 * 		Optionally, you can also call the file's save() method, this will turn the file into an entity in the
 * 		system and permit you to do things like attach tags to the file etc. This is not done automatically since
 * 		there are many occasions where you may want access to file data on datastores using the ElggFile interface
 * 		but do not want to create an Entity reference to it in the system (temporary files for example).
 *
 * @author Curverider Ltd
 */
class ElggFile extends ElggObject {
	/** Filestore */
	private $filestore;

	/** File handle used to identify this file in a filestore. Created by open. */
	private $handle;

	protected function initialise_attributes() {
		parent::initialise_attributes();

		$this->attributes['subtype'] = "file";
	}

	public function __construct($guid = null) {
		parent::__construct($guid);

		// Set default filestore
		$this->filestore = $this->getFilestore();
	}

	/**
	 * Set the filename of this file.
	 *
	 * @param string $name The filename.
	 */
	public function setFilename($name) {
		$this->filename = $name;
	}

	/**
	 * Return the filename.
	 */
	public function getFilename() {
		return $this->filename;
	}

	/**
	 * Return the filename of this file as it is/will be stored on the filestore, which may be different
	 * to the filename.
	 */
	public function getFilenameOnFilestore() {
		return $this->filestore->getFilenameOnFilestore($this);
	}

	/*
	 * Return the size of the filestore associated with this file
	 *
	 */
	public function getFilestoreSize($prefix='',$container_guid=0) {
		if (!$container_guid) {
			$container_guid = $this->container_guid;
		}
		$fs = $this->getFilestore();
		return $fs->getSize($prefix,$container_guid);
	}

	/**
	 * Get the mime type of the file.
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
	 * @param $mimetype The mimetype
	 */
	public function setMimeType($mimetype) {
		return $this->mimetype = $mimetype;
	}

	/**
	 * Set the optional file description.
	 *
	 * @param string $description The description.
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * Open the file with the given mode
	 *
	 * @param string $mode Either read/write/append
	 */
	public function open($mode) {
		if (!$this->getFilename()) {
			throw new IOException(elgg_echo('IOException:MissingFileName'));
		}

		// See if file has already been saved
		// seek on datastore, parameters and name?

		// Sanity check
		if (
			($mode!="read") &&
			($mode!="write") &&
			($mode!="append")
		) {
			throw new InvalidParameterException(sprintf(elgg_echo('InvalidParameterException:UnrecognisedFileMode'), $mode));
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
	 * Write some data.
	 *
	 * @param string $data The data
	 */
	public function write($data) {
		$fs = $this->getFilestore();

		return $fs->write($this->handle, $data);
	}

	/**
	 * Read some data.
	 *
	 * @param int $length Amount to read.
	 * @param int $offset The offset to start from.
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
	 */
	public function close() {
		$fs = $this->getFilestore();

		if ($fs->close($this->handle)) {
			$this->handle = NULL;

			return true;
		}

		return false;
	}

	/**
	 * Delete this file.
	 */
	public function delete() {
		$fs = $this->getFilestore();
		if ($fs->delete($this)) {
			return parent::delete();
		}
	}

	/**
	 * Seek a position in the file.
	 *
	 * @param int $position
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
		$fs = $this->getFilestore();

		return $fs->tell($this->handle);
	}

	/**
	 * Return the size of the file in bytes.
	 */
	public function size() {
		return $this->filestore->getFileSize($this);
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 */
	public function eof() {
		$fs = $this->getFilestore();

		return $fs->eof($this->handle);
	}

	public function exists() {
		$fs = $this->getFilestore();

		return $fs->exists($this);
	}

	/**
	 * Set a filestore.
	 *
	 * @param ElggFilestore $filestore The file store.
	 */
	public function setFilestore(ElggFilestore $filestore) {
		$this->filestore = $filestore;
	}

	/**
	 * Return a filestore suitable for saving this file.
	 * This filestore is either a pre-registered filestore, a filestore loaded from metatags saved
	 * along side this file, or the system default.
	 */
	protected function getFilestore() {
		// Short circuit if already set.
		if ($this->filestore) {
			return $this->filestore;
		}

		// If filestore meta set then retrieve filestore
		// @todo Better way of doing this?
		$metas = get_metadata_for_entity($this->guid);
		$parameters = array();
		if (is_array($metas)) {
			foreach ($metas as $meta) {
				if (strpos($meta->name, "filestore::")!==false) {
					// Filestore parameter tag
					$comp = explode("::", $meta->name);
					$name = $comp[1];

					$parameters[$name] = $meta->value;
				}
			}
		}

		if (isset($parameters['filestore'])) {
			if (!class_exists($parameters['filestore'])) {
				$msg = sprintf(elgg_echo('ClassNotFoundException:NotFoundNotSavedWithFile'),
								$parameters['filestore'],
								$this->guid);
				throw new ClassNotFoundException($msg);
			}

			// Create new filestore object
			$this->filestore = new $parameters['filestore']();

			$this->filestore->setParameters($parameters);
		} else {
			// @todo - should we log error if filestore not set
		}


		// if still nothing then set filestore to default
		if (!$this->filestore) {
			$this->filestore = get_default_filestore();
		}

		return $this->filestore;
	}

	public function save() {
		if (!parent::save()) {
			return false;
		}

		// Save datastore metadata
		$params = $this->filestore->getParameters();
		foreach ($params as $k => $v) {
			$this->setMetaData("filestore::$k", $v);
		}

		// Now make a note of the filestore class
		$this->setMetaData("filestore::filestore", get_class($this->filestore));

		return true;
	}
}
