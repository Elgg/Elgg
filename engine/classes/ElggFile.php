<?php

use Elgg\Exceptions\Filesystem\IOException;
use Elgg\Exceptions\DomainException as ElggDomainException;
use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Project\Paths;
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
 * @property      string $mimetype         MIME type of the file
 * @property      string $simpletype       Category of the file
 * @property      string $originalfilename Filename of the original upload
 * @property      int    $upload_time      Timestamp of the upload action, used as a filename prefix
 * @property      string $filestore_prefix Prefix (directory) on user's filestore where the file is saved
 * @property-read string $filename         The filename of the file
 */
class ElggFile extends ElggObject {

	/**
	 * @var resource|null|false File handle used to identify this file in a filestore
	 * @see \ElggFile::open()
	 */
	private $handle;

	/**
	 * Set subtype to 'file'.
	 *
	 * @return void
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = 'file';
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'filename':
				// ensure sanitization
				$this->setFilename($value);
				return;
		}
		
		parent::__set($name, $value);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function __get($name) {
		switch ($name) {
			case 'filename':
				// ensure sanitization
				return $this->getFilename();
		}
		
		return parent::__get($name);
	}

	/**
	 * Set the filename of this file. This filename will be sanitized to prevent path traversal
	 *
	 * @param string $filename The filename
	 *
	 * @return void
	 */
	public function setFilename(string $filename): void {
		$filename = ltrim(Paths::sanitize($filename, false), '/');
		
		parent::__set('filename', $filename);
	}

	/**
	 * Return the filename. This filename will be sanitized to prevent path traversal
	 *
	 * @return string
	 */
	public function getFilename(): string {
		$filename = parent::__get('filename');
		if (empty($filename)) {
			return '';
		}
		
		return ltrim(Paths::sanitize($filename, false), '/');
	}

	/**
	 * Return the filename of this file as it is/will be stored on the
	 * filestore, which may be different to the filename.
	 *
	 * @return string
	 */
	public function getFilenameOnFilestore(): string {
		return $this->getFilestore()->getFilenameOnFilestore($this);
	}

	/**
	 * Get the mime type of the file.
	 * Returns mimetype metadata value if set, otherwise attempts to detect it.
	 *
	 * @return string|false
	 */
	public function getMimeType(): string|false {
		if ($this->mimetype) {
			return $this->mimetype;
		}
		
		try {
			return _elgg_services()->mimetype->getMimeType($this->getFilenameOnFilestore());
		} catch (ElggInvalidArgumentException $e) {
			// the file has no file on the filesystem
			// can happen in tests etc.
		}
		
		return false;
	}

	/**
	 * Set the mime type of the file.
	 *
	 * @param string $mimetype The mimetype
	 *
	 * @return void
	 */
	public function setMimeType(string $mimetype): void {
		$this->mimetype = $mimetype;
	}

	/**
	 * Get the simple type of the file.
	 * Returns simpletype metadata value if set, otherwise parses it from mimetype
	 *
	 * @return string 'document', 'audio', 'video', or 'general' if the MIME type was unrecognized
	 */
	public function getSimpleType(): string {
		if (isset($this->simpletype)) {
			return $this->simpletype;
		}
		
		return _elgg_services()->mimetype->getSimpleType($this->getMimeType() ?: '');
	}

	/**
	 * Open the file with the given mode
	 *
	 * @param string $mode Either read/write/append
	 *
	 * @return false|resource File handler
	 *
	 * @throws IOException
	 * @throws \Elgg\Exceptions\DomainException
	 */
	public function open(string $mode) {
		if (!$this->getFilename()) {
			throw new IOException('You must specify a name before opening a file.');
		}

		if (!in_array($mode, ['read', 'write', 'append'])) {
			throw new ElggDomainException("Unrecognized file mode '{$mode}'");
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
	public function write(string $data): int|false {
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
	public function read(int $length, int $offset = 0) {
		return $this->getFilestore()->read($this->handle, $length, $offset);
	}

	/**
	 * Gets the full contents of this file.
	 *
	 * @return false|string The file contents.
	 */
	public function grabFile(): string|false {
		return $this->getFilestore()->grabFile($this);
	}

	/**
	 * Close the file and commit changes
	 *
	 * @return bool
	 */
	public function close(): bool {
		if (is_resource($this->handle) && $this->getFilestore()->close($this->handle)) {
			$this->handle = null;

			return true;
		}

		return false;
	}

	/**
	 * Delete this file.
	 *
	 * @param bool $follow_symlinks If true, will also delete the target file if the current file is a symlink
	 *
	 * @return bool
	 */
	public function delete(bool $follow_symlinks = true): bool {
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
	public function seek(int $position): void {
		$this->getFilestore()->seek($this->handle, $position);
	}

	/**
	 * Return the current position of the file.
	 *
	 * @return int The file position
	 */
	public function tell(): int {
		return $this->getFilestore()->tell($this->handle);
	}

	/**
	 * Updates modification time of the file and clears stats cache for the file
	 *
	 * @return bool
	 */
	public function setModifiedTime(): bool {
		$filestorename = $this->getFilenameOnFilestore();
		
		$modified = touch($filestorename);
		if ($modified) {
			clearstatcache(true, $filestorename);
		} else {
			elgg_log("Unable to update modified time for {$filestorename}", 'ERROR');
		}
		
		return $modified;
	}

	/**
	 * Returns file modification time
	 *
	 * @return int
	 */
	public function getModifiedTime(): int {
		return filemtime($this->getFilenameOnFilestore());
	}

	/**
	 * Return the size of the file in bytes.
	 *
	 * @return int
	 * @since 1.9
	 */
	public function getSize(): int {
		return $this->getFilestore()->getFileSize($this);
	}

	/**
	 * Return a boolean value whether the file handle is at the end of the file
	 *
	 * @return bool
	 */
	public function eof(): bool {
		return $this->getFilestore()->eof($this->handle);
	}

	/**
	 * Returns if the file exists
	 *
	 * @return bool
	 */
	public function exists(): bool {
		return $this->getFilestore()->exists($this);
	}

	/**
	 * Return the system filestore based on dataroot.
	 *
	 * @return \Elgg\Filesystem\Filestore\DiskFilestore
	 */
	protected function getFilestore(): \Elgg\Filesystem\Filestore\DiskFilestore {
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
	 *
	 * @return bool
	 */
	public function transfer(int $owner_guid, string $filename = null): bool {
		if ($owner_guid < 1) {
			return false;
		}

		if (!$this->exists()) {
			return false;
		}

		if (empty($filename)) {
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
	 *
	 * @return bool
	 */
	public function acceptUploadedFile(UploadedFile $upload): bool {
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
		$filename = elgg_strtolower("{$prefix}/{$this->upload_time}{$this->originalfilename}");
		$this->setFilename($filename);
		$this->filestore_prefix = $prefix;

		$params = [
			'file' => $this,
			'upload' => $upload,
		];

		$uploaded = _elgg_services()->events->triggerResults('upload', 'file', $params);
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
			
			try {
				// try to detect mimetype
				$mime_type = _elgg_services()->mimetype->getMimeType($this->getFilenameOnFilestore());
				$this->setMimeType($mime_type);
				$this->simpletype = _elgg_services()->mimetype->getSimpleType($mime_type);
			} catch (ElggInvalidArgumentException $e) {
				// this can fail if the upload events returns true, but the file is not present on the filestore
				// this happens in a unittest
			}
			
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
	public function canDownload(int $user_guid = 0, bool $default = true): bool {
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
	 * @return string|null
	 */
	public function getDownloadURL(bool $use_cookie = true, string $expires = '+2 hours'): ?string {
		$file_svc = new \Elgg\FileService\File();
		$file_svc->setFile($this);
		if (!empty($expires)) {
			$file_svc->setExpires($expires);
		}
		
		$file_svc->setDisposition('attachment');
		$file_svc->bindSession($use_cookie);

		$params = [
			'entity' => $this,
			'use_cookie' => $use_cookie,
			'expires' => $expires,
		];
		return _elgg_services()->events->triggerResults('download:url', 'file', $params, $file_svc->getURL());
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
	 * @return string|null
	 */
	public function getInlineURL(bool $use_cookie = false, string $expires = ''): ?string {
		$file_svc = new \Elgg\FileService\File();
		$file_svc->setFile($this);
		if (!empty($expires)) {
			$file_svc->setExpires($expires);
		}
		
		$file_svc->setDisposition('inline');
		$file_svc->bindSession($use_cookie);

		$params = [
			'entity' => $this,
			'use_cookie' => $use_cookie,
			'expires' => $expires,
		];
		return _elgg_services()->events->triggerResults('inline:url', 'file', $params, $file_svc->getURL());
	}
}
