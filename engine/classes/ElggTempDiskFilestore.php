<?php
/**
 * A filestore that uses disk as storage.
 *
 * @warning This should be used by a wrapper class
 * like {@link \ElggFile}.
 *
 * @package    Elgg.Core
 * @subpackage FileStore.Disk
 * @since 3.0
 */
class ElggTempDiskFilestore extends \ElggDiskFilestore {
	
	/**
	 * @var string
	 */
	protected $unique_sub_dir;
	
	/**
	 * Construct a temp disk filestore using the given directory root.
	 *
	 * @param string $directory_root Root directory, must end in "/"
	 */
	public function __construct($directory_root = '') {
		
		if (!$directory_root) {
			$directory_root = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . '/';
		}
		
		$this->unique_sub_dir = uniqid() . '/';
		
		parent::__construct($directory_root);
	}

	/**
	 * Get the filename as saved on disk for an \ElggFile object
	 *
	 * Returns an empty string if no filename set
	 *
	 * @param \ElggFile $file File object
	 *
	 * @return string The full path of where the file is stored
	 */
	public function getFilenameOnFilestore(\ElggFile $file) {
		
		$filename = $file->getFilename();
		if (!$filename) {
			return '';
		}

		return $this->dir_root . $this->unique_sub_dir . $file->getFilename();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getParameters() {
		$params = parent::getParameters();
		$params['unique_sub_dir'] = $this->unique_sub_dir;
		
		return $params;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setParameters(array $parameters) {
		
		if (isset($parameters['unique_sub_dir'])) {
			$this->unique_sub_dir = $parameters['unique_sub_dir'];
		}

		return parent::setParameters($parameters);
	}
}
