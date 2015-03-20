<?php
use Gaufrette\Filesystem;

/**
 * A filestore that uses a Cloud storage service
 *
 * @warning This should be used by a wrapper class
 * like {@link \ElggFile}.
 */
class ElggCloudFilestore extends \ElggFilestore {
	/**
	 *
	 * @var Filesystem;
	 */
	private $fs;
	
	private $adapter;
	
	public function __construct($adapter = null) {
		if ($adapter) {
			$this->adapter = $adapter;
			$this->fs = $adapter->getFilesystem();
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function open(\ElggFile $file, $mode) {
		return $file->getFilenameOnFilestore();
	}

	/**
	 * {@inheritdoc}
	 */
	public function write($f, $data) {
		return $this->fs->write($f, $data);
	}

	/**
	 * {@inheritdoc}
	 * @todo length and offset are ignored.
	 */
	public function read($f, $length, $offset = 0) {
		return $this->fs->read($f);
	}

	/**
	 * {@inheritdoc}
	 */
	public function close($f) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function delete(\ElggFile $file) {
		$name = $file->getFilenameOnFilestore();
		try {
			return $this->fs->delete($name);
		} catch (Gaufrette\Exception\FileNotFound $e) {
			// it doesn't exist, so let it be removed from the db.
			return true;
		}
	}

	/**
	 * {@inheritdoc}
	 */
	public function seek($f, $position) {
		return 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function tell($f) {
		return 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function eof($f) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFileSize(\ElggFile $file) {
		return $this->fs->size($file->getFilenameOnFilestore());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getFilenameOnFilestore(\ElggFile $file) {
		$owner_guid = $file->getOwnerGuid();
		if (!$owner_guid) {
			$owner_guid = _elgg_services()->session->getLoggedInUserGuid();
		}

		if (!$owner_guid) {
			$msg = "File " . $file->getFilename() . " (file guid:" . $file->guid . ") is missing an owner!";
			throw new \InvalidParameterException($msg);
		}

		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}

		return $owner_guid . '/' . $file->getFilename();
	}

	/**
	 * {@inheritdoc}
	 */
	public function grabFile(\ElggFile $file) {
		return $this->fs->get($file->getFilenameOnFilestore())->getContent();
	}

	/**
	 * {@inheritdoc}
	 */
	public function exists(\ElggFile $file) {
		if (!$file->getFilename()) {
			return false;
		}
		return $this->fs->has($this->getFilenameOnFilestore($file));
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSize($prefix, $container_guid) {
		return $this->fs->size($key);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getParameters() {
		return array_merge(
			["adapter" => get_class($adapter)],
			$this->adapter->getParameters()
		);
	}

	/**
	 * This is like a second constructor that gets called from ElggFile()
	 * 
	 * {@inheritdoc}
	 */
	public function setParameters(array $parameters) {
		if (!isset($parameters['class_name'])) {
			throw new \ClassNotFoundException("Missing class name");
		}
		
		$class_name = $parameters['class_name'];
		if (!class_exists($class_name)) {
			throw new \ClassNotFoundException("Missing class name");
		}
		
		$this->adapter = new $class_name($parameters);
		$this->fs = $this->adapter->getFilesystem();
	}
	
	public function moveUploadedFile($from, $to) {
		if (!is_uploaded_file($from)) {
			return false;
		}
		
		$this->fs->write($to, fopen($from, 'rb'), true);
	}
	
	public function readfile(ElggFile $file) {
		$stream = $this->fs->createStream($this->getFilenameOnFilestore($file));
		$stream->open(new Gaufrette\StreamMode('rb'));
		
		while (!$stream->eof()) {
			echo $stream->read(256);
		}
		
		// @todo should be able to use readfile() with a context stream.
	}
}
